<!-- ======== @Region: #highlighted ======== -->
<div id="highlighted">
    <div class="container">
        <div class="header">
            <h2 class="page-title">
                <span>CRUD - Exercises</span>
            </h2>
        </div>
    </div>
</div>

<!-- ======== @Region: #content ======== -->
<div id="content">
    <div class="container portfolio">
        <div class="row">
            <div class="col-md-12">
                <h2>
                    CRUD Exercise - Edit exercise #<?= $this->formData["name"] ?>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" class="form-horizontal">
                    <input type="hidden" name="task" value="save">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Name</label>  
                            <div class="col-md-5">
                                <input value="<?php echo isset($this->formData["name"]) ? htmlspecialchars($this->formData["name"]) : ""; ?>" type="text" name="name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <?php if (!empty($this->formErrors["name"])) { ?>
                                    <ul style="color: red">
                                        <?php foreach ($this->formErrors["name"] as $errorMessage) { ?>
                                            <li class="error"><?php echo $errorMessage; ?></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                        <input type="hidden" name="submit" value="submit">
                    </fieldset>
                    <fieldset>
                        <legend></legend>
                        <div class="form-group text-right">
                            <a href="#" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>