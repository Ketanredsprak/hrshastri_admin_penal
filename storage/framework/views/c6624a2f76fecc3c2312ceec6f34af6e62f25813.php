<?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($employee->employee_id ?? ''); ?></td>
        <td><?php echo e($employee->name ?? ''); ?></td>
        <td><?php echo e($employee->branch->name ?? ''); ?></td>
        <td><?php echo e($employee->designation->name ?? ''); ?></td>

        <?php $__currentLoopData = $monthDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthDate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $attendance = $attendanceData[$monthDate] ?? null;
                $workingHours = 0;
                $late = '00:00';
                $earlyLeaving = '00:00';
            ?>

            <?php if(!empty($employeesWithMaxAttendance[$monthDate]) && $employeesWithMaxAttendance[$monthDate] > 0): ?>
                <?php
                    $checkData = $attendance['attendanceForDate']->where('employee_id', $employee->id);
                ?>

                <?php $__currentLoopData = $checkData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $clockInDateTime = '';
                        $clockOutDateTime = '';
                        
                        if (empty($childRow->clock_in_date_time) || $childRow->clock_in_date_time == '0000-00-00 00:00:00') {
                            $clockInDateTime = $childRow->getRawOriginal('date') . ' ' . $childRow->clock_in;
                        } else {
                            $clockInDateTime = $childRow->clock_in_date_time;
                        }
                        
                        if (empty($childRow->clock_out_date_time) || $childRow->clock_out_date_time == '0000-00-00 00:00:00') {
                            $clockOutDateTime = $childRow->getRawOriginal('date') . ' ' . $childRow->clock_out;
                        } else {
                            $clockOutDateTime = $childRow->clock_out_date_time;
                        }
                        
                        if ($childRow->clock_in != '00:00:00' || $childRow->clock_out != '00:00:00') {
                            if ($late == '00:00' && $childRow->late != '00:00:00') {
                                $late = date('H:i', strtotime($childRow->late));
                            }
                            $earlyLeaving = date('H:i', strtotime($childRow->early_leaving));
                            $from = new DateTime($clockInDateTime);
                            $to = new DateTime($clockOutDateTime);
                            $diff = $from->diff($to);
                            $totalMinutes =
                                    $diff->i +
                                    $diff->h * 60 +
                                    $diff->days * 24 * 60;

                            
                            $workingHours += $totalMinutes;
                        }
                    ?>

                    <td class="clockIn">
                    <?php
                        if ($childRow->clock_in != '' && $childRow->clock_in != '00:00:00') {
                            echo \Auth::user()->dateFormat($clockInDateTime) . ' ' . \Auth::user()->timeFormat($clockInDateTime);
                        }
                    ?>
                    </td>
                    <td class="clockOut">
                    <?php
                        if ($childRow->clock_out != '' && $childRow->clock_out != '00:00:00') {
                            echo \Auth::user()->dateFormat($clockOutDateTime) . ' ' . \Auth::user()->timeFormat($clockOutDateTime);
                        }
                    ?></td>
                    
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php for($i = 1; $i <= $employeesWithMaxAttendance[$monthDate] - $checkData->count(); $i++): ?>
                    <td class="clockIn"></td>
                    <td class="clockOut"></td>
                <?php endfor; ?>
            <?php else: ?>
                <td class="clockIn"></td>
                <td class="clockOut"></td>
            <?php endif; ?>

            <?php
                $working_hours = floor($workingHours / 60);
                $working_minutes = $workingHours % 60;
                $workingHours = $working_hours . ':' . $working_minutes;
            ?>
            <td class="workingHours"><?php echo e($workingHours); ?></td>

            <td class="workingHours"><?php echo e($late); ?></td>

            <td class="workingHours"><?php echo e($earlyLeaving); ?></td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /var/www/html/resources/views/report/advance_attendance_rows.blade.php ENDPATH**/ ?>