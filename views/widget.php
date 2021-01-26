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
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <h3 class="title-block"><?php echo _l('Звонки') ?></h3>
                            <!---->
                            <div class="calls-output">

                            </div>
                            <!---->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <h3 class="title-block"><?php echo _l('Задачи') ?></h3>
                            <!---->
                            <div class="tasks-output">

                            </div>
                            <!---->
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h3 class="title-block"><?php echo _l('Лиды') ?></h3>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <!---->
                                    <div class="lead-status-output">

                                    </div>
                                    <!---->
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <!---->
                                    <div class="lead-source-output">

                                    </div>
                                    <!---->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>