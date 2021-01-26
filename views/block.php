<?php foreach ($data['view'] as $key => $item) { ?>
    <?php $per = $item['value'] === 0 ? $item['value'] : round($item['value'] / ($data['total'] / 100)) ?>
    <div class="stat-block">
        <div class="stat">
            <div class="progress-circle p<?php echo $per ?> <?php echo $per > 50 ? 'over50' : '' ?> <?php echo $item['color'] ?>">
                <span class="x2 percent"><?php echo $per ?>%</span>
                <div class="left-half-clipper">
                    <div class="first50-bar"></div>
                    <div class="value-bar"></div>
                </div>
            </div>
        </div>
        <div class="desc">
            <div class="title text-muted"><?php echo $item['name'] ?></div>
            <div class="text"><?php echo $item['value'] ?></div>
        </div>
    </div>
<?php } ?>