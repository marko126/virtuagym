<!-- ======== @Region: #highlighted ======== -->
<div id="highlighted">
    <div class="container">
        <div class="header">
            <h2 class="page-title">
                <span>CRUD - Users</span>
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
                    CRUD User - Add new user
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" class="form-horizontal">
                    <input type="hidden" name="task" value="save">
                    <fieldset>
                        <legend>Credentials</legend>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email</label>  
                            <div class="col-md-5">
                                <input value="<?php echo isset($this->formData["email"]) ? htmlspecialchars($this->formData["email"]) : ""; ?>" type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <?php if (!empty($this->formErrors["email"])) { ?>
                                    <ul style="color: red">
                                        <?php foreach ($this->formErrors["email"] as $errorMessage) { ?>
                                            <li class="error"><?php echo $errorMessage; ?></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Password</label>  
                            <div class="col-md-5">
                                <input value="" type="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <?php if (!empty($this->formErrors["password"])) { ?>
                                    <ul style="color: red">
                                        <?php foreach ($this->formErrors["password"] as $errorMessage) { ?>
                                            <li class="error"><?php echo $errorMessage; ?></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Confirm Password</label>  
                            <div class="col-md-5">
                                <input value="" type="password" name="confirm_password" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <?php if (!empty($this->formErrors["confirm_password"])) { ?>
                                    <ul style="color: red">
                                        <?php foreach ($this->formErrors["confirm_password"] as $errorMessage) { ?>
                                            <li class="error"><?php echo $errorMessage; ?></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Personal data</legend>
                        <div class="form-group">
                            <label class="col-md-3 control-label">First Name</label>  
                            <div class="col-md-5">
                                <input value="<?php echo isset($this->formData["first_name"]) ? htmlspecialchars($this->formData["first_name"]) : ""; ?>" type="text" name="first_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <?php if (!empty($this->formErrors["first_name"])) { ?>
                                    <ul style="color: red">
                                        <?php foreach ($this->formErrors["first_name"] as $errorMessage) { ?>
                                            <li class="error"><?php echo $errorMessage; ?></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Last Name</label>  
                            <div class="col-md-5">
                                <input value="<?php echo isset($this->formData["last_name"]) ? htmlspecialchars($this->formData["last_name"]) : ""; ?>" type="text" name="last_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <?php if (!empty($this->formErrors["last_name"])) { ?>
                                    <ul style="color: red">
                                        <?php foreach ($this->formErrors["last_name"] as $errorMessage) { ?>
                                            <li class="error"><?php echo $errorMessage; ?></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Status</label>  
                            <div class="col-md-5">
                                <input value="<?php echo isset($this->formData["status"]) ? htmlspecialchars($this->formData["status"]) : ""; ?>" type="text" name="status" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <?php if (!empty($this->formErrors["status"])) { ?>
                                    <ul style="color: red">
                                        <?php foreach ($this->formErrors["status"] as $errorMessage) { ?>
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