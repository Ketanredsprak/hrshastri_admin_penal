<?php echo e(Form::model($timeSheet, ['route' => ['timesheet.update', $timeSheet->id], 'method' => 'PUT'])); ?>


<div class="modal-body">

    <div class="row">

        

        <div class="form-group col-md-6">

            <?php echo e(Form::label('date', __('Date'), ['class' => 'col-form-label'])); ?> <span class="text-danger"> * </span>

            <?php echo e(Form::date('date', $timeSheet->getRawOriginal('date'), ['class' => 'form-control d_week', 'id' => 'date', 'onblur' => 'date_currunt();', 'autocomplete' => 'off'])); ?>


            <label style="display:none" generated="true" class="errorAll" id="date_currunt_error"
                for="date_currunt_error">&nbsp;</label>

        </div>

        <div class="form-group col-md-6">

            <?php echo e(Form::label('hours', __('Hours'), ['class' => 'col-form-label'])); ?> <span class="text-danger"> * </span>

            <?php echo e(Form::number('hours', $timeSheet->hours, ['class' => 'form-control', 'id' => 'hours', 'onblur' => 'hours_date();', 'step' => '0.01'])); ?>


            <label style="display:none" generated="true" class="errorAll" id="hours_error"
                for="hours_error">&nbsp;</label>

        </div>

        <div class="form-group  col-md-12">

            <?php echo e(Form::label('remark', __('Remark'), ['class' => 'col-form-label'])); ?>


            <?php echo Form::textarea('remark', $timeSheet->remark, [
                'class' => 'form-control',
                'rows' => '2',
                'placeholder' => 'Enter remark',
            ]); ?>


        </div>

    </div>

</div>

<div class="modal-footer">

    <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>

    <button type="submit" onclick="return submit_data();" name="submit" class="btn btn-primary">Update</button>

</div>

<?php echo e(Form::close()); ?>




<script>
    function submit_data() {

        var status = true;

        if (date_currunt() == false) {

            status = false;

        }

        if (hours_date() == false) {

            status = false;

        }

        return status;

    }



    function date_currunt() {

        var date_currunt = $("#date").val()

        if (date_currunt == '') {

            jQuery("#date_currunt").removeClass("right");

            jQuery("#date_currunt").next("span").removeClass("right");

            jQuery("#date_currunt").addClass("wrong");

            jQuery("#date_currunt").next("span").addClass("wrong");

            jQuery("#date_currunt").css("border", "1px solid red");

            jQuery("#date_currunt_error").css("display", "");

            jQuery("#date_currunt_error").css("color", "red");

            jQuery("#date_currunt_error").html('This field is required');

            return false;

        } else {

            jQuery("#date_currunt").removeClass("wrong");

            jQuery("#date_currunt").next("span").removeClass("wrong");

            jQuery("#date_currunt").addClass("right");

            jQuery("#date_currunt").next("span").addClass("right");

            jQuery("#date_currunt").css("border", "1px solid #ccc");

            jQuery("#date_currunt_error").css("display", "none");

            return true;

        }

    }



    function hours_date() {

        var hours = $("#hours").val()

        if (hours == '') {

            jQuery("#hours").removeClass("right");

            jQuery("#hours").next("span").removeClass("right");

            jQuery("#hours").addClass("wrong");

            jQuery("#hours").next("span").addClass("wrong");

            jQuery("#hours").css("border", "1px solid red");

            jQuery("#hours_error").css("display", "");

            jQuery("#hours_error").css("color", "red");

            jQuery("#hours_error").html('This field is required');

            return false;

        } else {

            jQuery("#hours").removeClass("wrong");

            jQuery("#hours").next("span").removeClass("wrong");

            jQuery("#hours").addClass("right");

            jQuery("#hours").next("span").addClass("right");

            jQuery("#hours").css("border", "1px solid #ccc");

            jQuery("#hours_error").css("display", "none");

            return true;

        }

    }
</script>
<?php /**PATH /var/www/html/resources/views/timeSheet/edit.blade.php ENDPATH**/ ?>