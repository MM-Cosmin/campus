<?php


namespace Larabuild\Pagebuilder\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Larabuild\Pagebuilder\Models\Page;
use Larabuild\Optionbuilder\Facades\Settings;
use Larabuild\Pagebuilder\Facades\PageSettings;

class PageBuilderController extends Controller {

    public $theme_pb_path;
    public $theme;

    public function __construct($theme='edulia', $theme_pb_path = 'views/themes/edulia/pagebuilder')
        {
            $this->theme = activeTheme();
            $this->theme_pb_path = 'views/themes/'.activeTheme().'/pagebuilder';
        }

    public function build($pageId, Request $request) {

        
        $page = PageSettings::getPage($pageId);

        if (config('app.app_sync') && $pageId < 18) {
            Toastr::error('Restricted in demo mode');
            return back();
        }

        if (!$page)
            abort(404);

        $grid_templates =   [];
        foreach (getGrids() as $grid) {
            $columns = getColumnInfo($grid);
            $grid_templates[$grid] = view('pagebuilder::components.add-grid-placeholder', compact('columns', 'grid'))->render();
        }

        $components = $componentDirectories = $tempaltes = $componentTabs = [];

        if (file_exists(resource_path($this->theme_pb_path)))
            $componentDirectories = array_diff(scandir(resource_path($this->theme_pb_path)), array('..', '.'));
            foreach ($componentDirectories as $directory) {
                if( 'header-content' != $directory && 'footer-content' != $directory && 'footer-widget' != $directory && 'footer-copyright' != $directory){
                    if (file_exists(resource_path( $this->theme_pb_path.'/'. $directory . '/settings.php'))) {
                        $currentSettings = include resource_path( $this->theme_pb_path.'/'. $directory . '/settings.php');
                        
                    
                        if (!empty($currentSettings['id']) && $directory == $currentSettings['id']) {
        
                            setCurrentDirectory($currentSettings['id']);
                            $components[$currentSettings['id']] = array(
                                'directory' => $directory,
                                'settings' => $currentSettings,
                                'template' => view()->exists('themes.'.$this->theme.'.pagebuilder.' . $directory . '.view') ? view('themes.'.$this->theme.'.pagebuilder.' . $directory . '.view')->render() : view('pagebuilder::components.no-view')->render(),
                            );
                        
                            $componentTabs[$currentSettings['tab']][$currentSettings['id']] = $currentSettings['id'];
                        }
                    }
                }
                
            }


        setCurrentPageId($page->id);

        return view('pagebuilder::pagebuilder', compact('grid_templates', 'components', 'page', 'componentTabs'));
    }
    public function iframe($pageId, Request $request) {
        $page = PageSettings::getPage($pageId);
        $edit = true;
        if (!$page)
            abort(404);

        $grid_templates =   [];
        foreach (getGrids() as $grid) {
            $columns = getColumnInfo($grid);
            $grid_templates[$grid] = view('pagebuilder::components.add-grid-placeholder', compact('columns', 'grid'))->render();
        }

        $components = $componentDirectories = $tempaltes = $componentTabs = [];

        if (file_exists(resource_path($this->theme_pb_path)))
            $componentDirectories = array_diff(scandir(resource_path($this->theme_pb_path)), array('..', '.'));

        foreach ($componentDirectories as $directory) {

            if (file_exists(resource_path($this->theme_pb_path .'/'.  $directory . '/settings.php'))) {
                $currentSettings = include resource_path($this->theme_pb_path .'/'. $directory . '/settings.php');

                if (!empty($currentSettings['id']) && $directory == $currentSettings['id']) {

                    setCurrentDirectory($currentSettings['id']);


                    $components[$currentSettings['id']] = array(
                        'directory' => $directory,
                        'settings' => $currentSettings,
                        'template' => view()->exists('themes.'.$this->theme.'.pagebuilder.' . $directory . '.view') ? view('themes.'.$this->theme.'.pagebuilder.' . $directory . '.view', compact('page', 'edit'))->render() : view('pagebuilder::components.no-view', compact('page', 'edit'))->render(),
                    );


                    $componentTabs[$currentSettings['tab']][$currentSettings['id']] = $currentSettings['id'];
                }
            }
        }


        setCurrentPageId($page->id);
        
        return view('pagebuilder::pagebuilder-iframe', compact('grid_templates', 'components', 'page', 'componentTabs', 'edit'));
    }

    public function renderPage($pageSlug) {
        if(request()->get('preview') && !auth()->check()){
            return redirect()->back();
        }
        $headerPageData = Page::where('school_id',app('school')->id)->where('name', 'header_menu')
                    ->select('id', 'name', 'title', 'description', 'slug', 'settings', 'status','school_id')
                    ->firstOrFail();
        $headerMenu = view('pagebuilder::components.header-footer-page-components', ['page' => $headerPageData]);


        $page =  Page::where('school_id',app('school')->id)->select('id', 'name', 'title', 'description', 'slug', 'settings', 'status')
            ->whereSlug($pageSlug)
            ->when(empty(request()->get('preview')), function ($q) {
                return $q->whereStatus('published');
            })
            ->firstOrFail();

        $pageSections = view('pagebuilder::components.page-components', compact('page'));

        
        $footerPage = Page::where('school_id',app('school')->id)->where('name', 'footer_menu')
                    ->select('id', 'name', 'title', 'description', 'slug', 'settings', 'status','school_id','school_id')
                    ->firstOrFail();
                    

        $footerMenu = view('pagebuilder::components.header-footer-page-components', ['page' => $footerPage]);

        setCurrentPageId($page->id);
        
        return view('pagebuilder::page', compact('page', 'pageSections', 'headerMenu', 'footerMenu'));
    }

    public function getSettings(Request $request) {
        $pageId = $request->input('page_id');
        $directory = $request->input('directory');
        $sectionId = $request->input('id');
        $gridId = $request->input('grid_id');
        if (file_exists(resource_path($this->theme_pb_path . '/' . $directory . '/settings.php')))
            $settings = include resource_path($this->theme_pb_path . '/' . $directory . '/settings.php');
        if (!empty($settings['fields'])) {
            $settingsHtml = $this->getPageSectionSettings($pageId, $sectionId, $settings['fields']);
        }

        $styles = $this->getSectionStyles($pageId, $gridId);

        $json = ['type' => 'success', 'section_data' => PageSettings::getPageSettings($pageId) ?? [], 'settings' => $settingsHtml ?? '', 'styles' => $styles];

        return response(json_encode($json), 200);
    }




    public function setPageSettings(Request $request) {
        $settings = $sectionId = null;
        if (!empty($request->get('settings')))
            $settings = $request->get('settings');
        if (!empty($request->get('current_section_data'))) {
            parse_str($request->get('current_section_data'), $form_data);
            parse_str($request->get('current_advanced_settings'), $advanced_form_data);
            $sectionId = $form_data['section_id'];
            unset($form_data['_method']);
            unset($form_data['_token']);
            unset($form_data['section_id']);
            $settings['section_data'][$sectionId]['settings'] = [];
            foreach ($form_data as $key => $value) {
                $isArray = 0;
                if (is_array($value))
                    $isArray = 1;
                $settings['section_data'][$sectionId]['settings'][$key]['value'] = $value;
                $settings['section_data'][$sectionId]['settings'][$key]['is_array'] = $isArray;
            }

            $grid_id = $advanced_form_data['grid_id'] ?? null;
            unset($advanced_form_data['grid_id']);
            $settings['section_data'][$grid_id]['styles'] = [];
            foreach ($advanced_form_data as $key => $value) {
                if ($value != 'rgba(0,0,0,0)') {
                    $settings['section_data'][$grid_id]['styles'][$key] = $value;
                }
            }

            if (empty($settings['section_data'][$grid_id]['styles']['content_width']) || (!empty($settings['section_data'][$grid_id]['styles']['content_width']) && $settings['section_data'][$grid_id]['styles']['content_width'] == 'full_width'))
                unset($settings['section_data'][$grid_id]['styles']['boxed_slider_input']);
        }

        PageSettings::setPageSettings($request->input('page_id'), $settings);
        if (!empty($sectionId)) {
            setCurrentPageId($request->input('page_id'));
            $sectionHtml = $this->getSectionHtml($sectionId, $request->input('directory'));
        }
        return response()->json(
            [
                'success' => [
                    'type'          => 'success',
                    'title'         => __('Congratulations'),
                    'message'       => __('Page settings updated successfully'),
                ],
                'html' => $sectionHtml ?? '',
                'css' => !empty($grid_id) ? getComponentStyles($grid_id) : '',
                'custom_attributes' => !empty($grid_id) ? getCustomAttributes($grid_id) : '',
                'bgOverlay' => !empty($grid_id) ? getBgOverlay($grid_id) : '',
                'classes' => !empty($grid_id) ? getClasses($grid_id) : '',
                'sectionData' =>  $settings['section_data'] ?? []
            ]
        );
    }

    public function getSectionHtml($sectionId, $directory = null) {
        $html = '';
        if ($sectionId && $directory) {
            $edit = true;
            setSectionId($sectionId);
            $html = view()->exists('themes.'.$this->theme.'.pagebuilder.' . $directory . '.view') ?  view('themes.'.$this->theme.'.pagebuilder.' . $directory . '.view', compact('edit'))->render() : __('pagebuilder::pagebuilder.no_view', ["block" => $directory]);
        }
        return $html;
    }

    public function getPageSectionSettings($pageId, $sectionId, $fields) {


        $html = $db_value = '';

        if (is_array($fields) && !empty($fields)) {

            $tab_key = !empty($params['tab_key']) ? $params['tab_key'] :  '';
            $html = '<ul class="op-themeform__wrap">';
            foreach ($fields as $field) {

                $field['tab_key']       = $tab_key;
                if (empty($params['repeater_id'])) {
                    $id = !empty($field['id']) ? $field['id'] : '';
                    $db_value = PageSettings::getPageSectionSettings($pageId, $sectionId, $id);
                    $field['db_value']   = $db_value;
                    if (!empty($db_value)) {
                        $field['value']   = $db_value;
                    }
                } else {
                    $field['repeater_id']   = !empty($params['repeater_id']) ? $params['repeater_id'] :  '';
                    $field['index']         = !empty($params['repeater_id']) ? $params['index'] :  '';
                }
                $html .= Settings::getField($field);
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public function getPageSectionSettingsArray($pageId, $sectionId, $fields, $directory) {

        $settings = [];
        if (is_array($fields) && !empty($fields)) {
            foreach ($fields as $field) {
                $id = !empty($field['id']) ? $field['id'] : '';
                $db_value = PageSettings::getPageSectionSettings($pageId, $sectionId, $id);

                if (empty($db_value))
                    $db_value =  getDefaultValues($directory, $id);

                $settings[$id]   = $db_value;
            }
        }
        return $settings;
    }

    public function getSectionStyles($pageId, $sectionId) {
        $page = PageSettings::getPage($pageId);
        $styles = $page->settings['section_data'][$sectionId]['styles'] ?? [];
        return view('pagebuilder::components.styles', ['styles' => $styles])->render();
    }


    // Header
    public function header(Request $request) {
        $page = PageSettings::getHeaderFooterPage('header_menu');

        if (!$page)
            abort(404);

        $grid_templates =   [];
        foreach (getGrids() as $grid) {
            $columns = getColumnInfo($grid);
            $grid_templates[$grid] = view('pagebuilder::components.add-grid-placeholder', compact('columns', 'grid'))->render();
        }

        $components = $componentDirectories = $tempaltes = $componentTabs = [];

        if (file_exists(resource_path( $this->theme_pb_path)))
            $componentDirectories = array_diff(scandir(resource_path($this->theme_pb_path)), array('..', '.'));
            
        foreach ($componentDirectories as $directory) {
           if('header-content' == $directory){
            if (file_exists(resource_path( $this->theme_pb_path.'/'. $directory . '/settings.php'))) {
                $currentSettings = include resource_path( $this->theme_pb_path.'/'. $directory . '/settings.php');
                
               
                if (!empty($currentSettings['id']) && $directory == $currentSettings['id']) {

                    setCurrentDirectory($currentSettings['id']);
                    $components[$currentSettings['id']] = array(
                        'directory' => $directory,
                        'settings' => $currentSettings,
                        'template' => view()->exists('themes.'.$this->theme.'.pagebuilder.' . $directory . '.view') ? view('themes.'.$this->theme.'.pagebuilder.' . $directory . '.view')->render() : view('pagebuilder::components.no-view')->render(),
                    );
                   
                    $componentTabs[$currentSettings['tab']][$currentSettings['id']] = $currentSettings['id'];
                }
            }
           }
        }
        setCurrentPageId($page->id);
        return view('pagebuilder::pagebuilder', compact('grid_templates', 'components', 'page', 'componentTabs'));
    }

    public function footer(Request $request) {
        $page = PageSettings::getHeaderFooterPage('footer_menu');

        if (!$page)
            abort(404);

        $grid_templates =   [];
        foreach (getGrids() as $grid) {
            $columns = getColumnInfo($grid);
            $grid_templates[$grid] = view('pagebuilder::components.add-grid-placeholder', compact('columns', 'grid'))->render();
        }

        $components = $componentDirectories = $tempaltes = $componentTabs = [];

        if (file_exists(resource_path( $this->theme_pb_path)))
            $componentDirectories = array_diff(scandir(resource_path($this->theme_pb_path)), array('..', '.'));
            
        foreach ($componentDirectories as $directory) {
           if('footer-content' == $directory || 'footer-copyright' == $directory || 'footer-widget' == $directory){
            if (file_exists(resource_path( $this->theme_pb_path.'/'. $directory . '/settings.php'))) {
                $currentSettings = include resource_path( $this->theme_pb_path.'/'. $directory . '/settings.php');
                
               
                if (!empty($currentSettings['id']) && $directory == $currentSettings['id']) {

                    setCurrentDirectory($currentSettings['id']);
                    $components[$currentSettings['id']] = array(
                        'directory' => $directory,
                        'settings' => $currentSettings,
                        'template' => view()->exists('themes.'.$this->theme.'.pagebuilder.' . $directory . '.view') ? view('themes.'.$this->theme.'.pagebuilder.' . $directory . '.view')->render() : view('pagebuilder::components.no-view')->render(),
                    );
                   
                    $componentTabs[$currentSettings['tab']][$currentSettings['id']] = $currentSettings['id'];
                }
            }
           }
        }
        setCurrentPageId($page->id);
        return view('pagebuilder::pagebuilder', compact('grid_templates', 'components', 'page', 'componentTabs'));
    }
}
