<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAiContentSidebarmenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ai_contents = array(
            'aicontent' => array(
                'module' => 'AiContent',
                'sidebar_menu' => 'aicontent',
                'name' => 'Ai Content',
                'lang_name' => 'aicontent::aicontent.ai_content',
                'icon' => 'fas fa-robot',
                'svg' => null,
                'route' => 'aicontent',
                'parent_route' => null,
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 19,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 1,
                'old_id' => null,
                'child' => array(
                    'settings' => array(
                        'module' => 'AiContent',
                        'sidebar_menu' => null,
                        'name' => 'Settings',
                        'lang_name' => 'aicontent::aicontent.settings',
                        'icon' => null,
                        'svg' => null,
                        'route' => 'ai-content.settings',
                        'parent_route' => 'aicontent',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 1,
                        'is_saas' => 0,
                        'is_menu' => 1,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 2,
                        'old_id' => null,
                        'child' => array(
                            'update' => array(
                                'module' => null,
                                'sidebar_menu' => null,
                                'name' => 'Update',
                                'lang_name' => null,
                                'icon' => null,
                                'svg' => null,
                                'route' => 'ai-content.settings-update',
                                'parent_route' => 'ai-content.settings',
                                'is_admin' => 1,
                                'is_teacher' => 0,
                                'is_student' => 0,
                                'is_parent' => 0,
                                'position' => 1,
                                'is_saas' => 0,
                                'is_menu' => 0,
                                'status' => 1,
                                'menu_status' => 1,
                                'relate_to_child' => 0,
                                'alternate_module' => null,
                                'permission_section' => 0,
                                'user_id' => null,
                                'type' => 3,
                                'old_id' => null,
                            ),
                        ),
                    ),
                    'content' => array(
                        'module' => 'AiContent',
                        'sidebar_menu' => null,
                        'name' => 'Content',
                        'lang_name' => 'aicontent::aicontent.content',
                        'icon' => null,
                        'svg' => null,
                        'route' => 'ai-content.content',
                        'parent_route' => 'aicontent',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 1,
                        'is_saas' => 0,
                        'is_menu' => 1,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 2,
                        'old_id' => null,
                        'child' => array(
                            'update' => array(
                                'module' => null,
                                'sidebar_menu' => null,
                                'name' => 'Update',
                                'lang_name' => null,
                                'icon' => null,
                                'svg' => null,
                                'route' => 'ai-content.update',
                                'parent_route' => 'ai-content.content',
                                'is_admin' => 1,
                                'is_teacher' => 0,
                                'is_student' => 0,
                                'is_parent' => 0,
                                'position' => 1,
                                'is_saas' => 0,
                                'is_menu' => 0,
                                'status' => 1,
                                'menu_status' => 1,
                                'relate_to_child' => 0,
                                'alternate_module' => null,
                                'permission_section' => 0,
                                'user_id' => null,
                                'type' => 3,
                                'old_id' => null,
                            ),
                            'delete' => array(
                                'module' => null,
                                'sidebar_menu' => null,
                                'name' => 'Delete',
                                'lang_name' => null,
                                'icon' => null,
                                'svg' => null,
                                'route' => 'ai-content.delete',
                                'parent_route' => 'ai-content.content',
                                'is_admin' => 1,
                                'is_teacher' => 0,
                                'is_student' => 0,
                                'is_parent' => 0,
                                'position' => 1,
                                'is_saas' => 0,
                                'is_menu' => 0,
                                'status' => 1,
                                'menu_status' => 1,
                                'relate_to_child' => 0,
                                'alternate_module' => null,
                                'permission_section' => 0,
                                'user_id' => null,
                                'type' => 3,
                                'old_id' => null,
                            ),
                            'generate-text' => array(
                                'module' => null,
                                'sidebar_menu' => null,
                                'name' => 'Generate Text',
                                'lang_name' => null,
                                'icon' => null,
                                'svg' => null,
                                'route' => 'ai-content.generate_text',
                                'parent_route' => 'ai-content.content',
                                'is_admin' => 1,
                                'is_teacher' => 0,
                                'is_student' => 0,
                                'is_parent' => 0,
                                'position' => 1,
                                'is_saas' => 0,
                                'is_menu' => 0,
                                'status' => 1,
                                'menu_status' => 1,
                                'relate_to_child' => 0,
                                'alternate_module' => null,
                                'permission_section' => 0,
                                'user_id' => null,
                                'type' => 3,
                                'old_id' => null,
                            ),
                        ),
                    ),
                ),
            )
        );
        foreach ($ai_contents as $data) {
            storePermissionData($data);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {
        });
    }
}
