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
                            <button class="btn btn-primary" type="submit">Поиск</button>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                    <hr class="hr-panel-heading-dashboard">
                    <div class="row output-statistics">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <h3 class="title-block">Звонков</h3>

                            <div class="stat-block calls-missed">
                                <div class="stat">
                                    <div class="progress-circle p0 primary">
                                        <span class="x2 percent">0%</span>
                                        <div class="left-half-clipper">
                                            <div class="first50-bar"></div>
                                            <div class="value-bar"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="desc">
                                    <div class="title text-muted"><?php echo _l('Пропущенных') ?></div>
                                    <div class="text"><i class="fa fa-spin fa-refresh"></i></div>
                                </div>
                            </div>

                            <div class="stat-block calls-answered">
                                <div class="stat">
                                    <div class="progress-circle p0 primary">
                                        <span class="x2 percent">0%</span>
                                        <div class="left-half-clipper">
                                            <div class="first50-bar"></div>
                                            <div class="value-bar"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="desc">
                                    <div class="title text-muted"><?php echo _l('Отвеченных') ?></div>
                                    <div class="text"><i class="fa fa-spin fa-refresh"></i></div>
                                </div>
                            </div>

                            <div class="stat-block calls-outgoing">
                                <div class="stat">
                                    <div class="progress-circle p0 primary">
                                        <span class="x2 percent">0%</span>
                                        <div class="left-half-clipper">
                                            <div class="first50-bar"></div>
                                            <div class="value-bar"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="desc">
                                    <div class="title text-muted"><?php echo _l('Исходящих') ?></div>
                                    <div class="text"><i class="fa fa-spin fa-refresh"></i></div>
                                </div>
                            </div>

                            <div class="stat-block calls-incoming">
                                <div class="stat">
                                    <div class="progress-circle p0 primary">
                                        <span class="x2 percent">0%</span>
                                        <div class="left-half-clipper">
                                            <div class="first50-bar"></div>
                                            <div class="value-bar"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="desc">
                                    <div class="title text-muted"><?php echo _l('Входящих') ?></div>
                                    <div class="text"><i class="fa fa-spin fa-refresh"></i></div>
                                </div>
                            </div>
<!---->
<!--                            <br/>-->
<!--                            Всего-->
<!--                            <br/>-->
<!--                            <span class="calls-total"><i class="fa fa-spin fa-refresh"></i></span>-->
<!--                            <br>-->
<!--                            Пропущенных-->
<!--                            <br/>-->
<!--                            <span class="calls-missed"><i class="fa fa-spin fa-refresh"></i></span>-->
<!--                            <br>-->
<!--                            Отвеченных-->
<!--                            <br/>-->
<!--                            <span class="calls-answered"><i class="fa fa-spin fa-refresh"></i></span>-->
<!--                            <br/>-->
<!--                            Исходящих-->
<!--                            <br/>-->
<!--                            <span class="calls-outgoing"><i class="fa fa-spin fa-refresh"></i></span>-->
<!--                            <br/>-->
<!--                            Входящих-->
<!--                            <br/>-->
<!--                            <span class="calls-incoming"><i class="fa fa-spin fa-refresh"></i></span>-->
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>