<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$staffs = [];
if (is_staff_member()) {
    $this->load->model('staff_model');
    $staffs = $this->staff_model->get('', ['active' => 1]);
}
?>

<div class="widget" id="widget-<?php echo create_widget_id(); ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body padding-10">
                    <div class="widget-dragger"></div>
                    <?php echo form_open(null, ['id' => 'form-filter-kpi']) ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php echo render_date_input('from_date', null, '', ['placeholder' => _l('С какого')]); ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php echo render_date_input('to_date', null, '', ['placeholder' => _l('По какое')]); ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php echo render_select('staff_ids', $staffs, ['staffid', 'full_name']); ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <button class="btn btn-primary" type="submit"><?php echo _l('Поиск') ?></button>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                    <hr class="hr-panel-heading-dashboard">
                    <div class="row output-statistics">
                        <div class="col-xs-12">
                            <?php if (has_permission(PROCRM_KPI_MODULE_NAME, '', 'telephone')) { ?>
                                <!-- Звонки -->
                                <div class="calls-output block-output">
                                    <h3 class="title-block"><?php echo _l('telephone') ?></h3>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div id="calls-radial-bar" class="output-wg-block"></div>
                                        </div>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <div id="calls-growth-chart" class="output-wg-block"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <?php if (has_permission(PROCRM_KPI_MODULE_NAME, '', 'tasks')) { ?>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <!-- Задачи -->
                                        <div class="tasks-output block-output">
                                            <h3 class="title-block"><?php echo _l('tasks') ?></h3>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div id="tasks-block" class="output-wg-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (has_permission(PROCRM_KPI_MODULE_NAME, '', 'projects')) { ?>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <!-- Проекты -->
                                        <div class="projects block-output">
                                            <h3 class="title-block"><?php echo _l('projects') ?></h3>
                                            <div id="projects-block" class="output-wg-block"></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <?php if (has_permission(PROCRM_KPI_MODULE_NAME, '', 'leads')) { ?>
                                    <div class="col-md-8 col-sm-12">
                                        <h3 class="title-block"><?php echo _l('leads') ?></h3>
                                        <div class="rows">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <!-- Лиды статусы -->
                                                <div id="leads-statuses-block" class="output-wg-block"></div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <!-- Лиды откуда -->
                                                <div id="leads-sources-block" class="output-wg-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (has_permission(PROCRM_KPI_MODULE_NAME, '', 'contracts')) { ?>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <h3 class="title-block"><?php echo _l('contracts') ?></h3>
                                        <!-- Договоры -->
                                        <div id="contracts-block" class="output-wg-block"></div>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>