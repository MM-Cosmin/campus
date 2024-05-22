<?php

namespace Modules\AiContent\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\AiContent\Services\OpenAi;
use Modules\AiContent\Entities\AiModels;
use Modules\AiContent\Entities\AiTemplate;
use Modules\AiContent\Entities\AiContentSetting;
use Modules\AiContent\Entities\AiGeneratedContent;

class AiContentController extends Controller
{
    public function index()
    {
        try {
            $contents = AiGeneratedContent::where('school_id', app('school')->id)->with('template')->get();
            $creativities = AiModels::AI_CREATIVITY;
            $openAiModels = AiModels::OPEN_AI_MODELS;
            return view('aicontent::content.index', compact('contents', 'creativities', 'openAiModels'));
        } catch (\Exception $e) {
            Toastr::error('Operation failed', 'Error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            $content = AiGeneratedContent::find($request['id']);
            $content->output_text = $request['output'];
            $result = $content->save();
            if ($result) {
                Toastr::success(__('aicontent::aicontent.Updated Successfully'), trans('common.Success'));
                return redirect()->back();
            } else {
                Toastr::error(__('aicontent::aicontent.Update Failed'), __('aicontent::aicontent.Error'));
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error('Operation failed', 'Error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $content = AiGeneratedContent::find($id);
            $result = $content->delete();
            if ($result) {
                Toastr::success(__('aicontent::aicontent.Deleted Successfully'), trans('common.Success'));
                return redirect()->back();
            } else {
                Toastr::error(__('aicontent::aicontent.Delete Failed'), __('aicontent::aicontent.Error'));
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error('Operation failed', 'Error');
            return redirect()->back();
        }
    }

    public function textGenerator($request)
    {
        $contentSetting = AiContentSetting::first();
        $open_ai = new OpenAi($contentSetting->open_ai_secret_key);
        $text = '';
        $max_tokens = (int)$request['max_result_length'];
        $counter = 1;
        $max_results = (int)$request['number_of_result'];
        $temperature = (float)$request['creativity'];
        $ai_default_model = AiContentSetting::first();
        $model = $contentSetting->ai_default_model ?? 'gpt-3.5-turbo-instruct';
        if ($model == 'gpt-3.5-turbo' || $model == 'gpt-4' || $model == 'gpt-4-32k') {
            $complete = $open_ai->chat([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        "role" => "user",
                        "content" => $request['prompt']
                    ],
                ],
                'temperature' => $temperature,
                'max_tokens' => $max_tokens,
                'n' => $max_results,
            ]);
        } else {
            $complete = $open_ai->completion([
                'model' => $model,
                'prompt' => $request['prompt'],
                'temperature' => $temperature,
                'max_tokens' => $max_tokens,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
                'n' => $max_results,
            ]);
        }
        $response = json_decode($complete, true);
        $generated_response = [];
        if (isset($response['choices'])) {
            if ($model != 'gpt-3.5-turbo' && $model != 'gpt-4' && $model != 'gpt-4-32k') {
                if (count($response['choices']) > 1) {
                    foreach ($response['choices'] as $value) {
                        $result = trim($value['text']);
                        $result = str_replace('"', "", $result);
                        $generated_response[] = trim($result);
                        $text .= $counter . '. ' . ltrim($value['text']) . "\r\n\r\n\r\n";
                        $counter++;
                    }
                } else {
                    $result = trim($response['choices'][0]['text']);
                    $result = str_replace('"', "", $result);
                    $generated_response[] = trim($result);
                    $text = $response['choices'][0]['text'];
                }
            } else {
                if (count($response['choices']) > 1) {
                    foreach ($response['choices'] as $value) {
                        $result = trim($value['text']);
                        $result = str_replace('"', "", $result);
                        $generated_response[] = trim($result);
                        $text .= $counter . '. ' . trim($value['message']['content']) . "\r\n\r\n\r\n";
                        $counter++;
                    }
                } else {
                    $result = trim($response['choices'][0]['message']['content']);
                    $result = str_replace('"', "", $result);
                    $generated_response[] = trim($result);
                    $text = ltrim($response['choices'][0]['message']['content']);
                }
            }
            $tokens = $response['usage']['completion_tokens'];
            $data['results'] = $generated_response;
            $data['prompt'] = $request['prompt'];
            $data['text'] = str_replace('"', "", trim($text));
            $data['status'] = 'success';
            $content_data = [
                'user_id' => auth()->user()->id,
                'input_text' => request('keyword'),
                'output_text' => $data['text'],
                'model' => $model,
                'tokens' => $tokens,
                'words' => request('max_result_length'),
                'temperature' => request('creativity'),
                'template_id' => request('template_id'),
                'lang' => request('language'),
            ];
            $this->saveResult($content_data);
            return $data;
        } elseif (isset($response['error'])) {
            $data['status'] = 'error';
            $data['message'] = $response['error']['message'] ? 'Max Result Length can\'t be negative' : __('aicontent::aicontent.Text was not generated, please try again');
            return $data;
        } else {
            $data['status'] = 'error';
            $data['message'] = __('aicontent::aicontent.Text was not generated, please try again');
            return $data;
        }
    }

    function saveResult($response_data)
    {
        try {
            $content = new AiGeneratedContent();
            $content->user_id = $response_data['user_id'];
            $content->input_text = $response_data['input_text'];
            $content->output_text = $response_data['output_text'];
            $content->model = $response_data['model'];
            $content->tokens = $response_data['tokens'];
            $content->words = $response_data['words'];
            $content->temperature = $response_data['temperature'];
            $content->template_id = $response_data['template_id'];
            $content->lang = $response_data['lang'];
            $content->save();
        } catch (\Throwable $th) {
        }
    }

    function commandFormating($data)
    {
        try {
            $template = $data['template'];
            $tone = $data['tone'];
            $keyword = $data['keyword'];
            if ($data['title']) {
                $title = $data['title'];
                $template = str_replace('{title}', $title, $template);
            }
            $template = str_replace('{keywords}', $keyword, $template);
            $template = str_replace('{tone}', trans('ai-content.' . $tone), $template);
            return $template;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    function getTemplateMessage($request)
    {
        $template_details = AiTemplate::where('id', $request['template_id'])->first();
        $template_content = $template_details->template_content->content;
        $language = $request['language'] ?? 'english';
        $template_title = "Provide response in " . $language . " language.\n\n ";
        if ($request['title']) {
            $template_title .= "Use {title} as a title.\n\n" . $template_content . ".\n\n " . 'Use following keywords : {keywords}. ' . ".\n\n ";
        } else {
            $template_title .= $template_content . ".\n\n " . 'Use following keywords : {keywords}. ' . ".\n\n ";
        }
        $template_title .= 'Tone of voice must be' . ': {tone}';
        $data['template'] = $template_title;
        $data['tone'] = $request['tone'];
        $data['keyword'] = $request['keyword'];
        $data['title'] = $request['title'];
        $data['max_result_length'] = $request['max_result_length'];
        $data['number_of_result'] = $request['number_of_result'];
        $data['creativity'] = $request['creativity'];
        $command = $this->commandFormating($data);
        $data['prompt'] = $command;
        return $this->textGenerator($data);
    }

    public function generate(Request $request)
    {
        $request = $request->all();
        $result = $this->getTemplateMessage($request);
        return response()->json($result);
    }
}
