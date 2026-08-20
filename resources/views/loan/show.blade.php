{{ Form::open(['url' => 'leave/changeaction_hr', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Loan Status') }}</th>
                    <td>{{ $loan_status_text }}</td>
                </tr>
                <tr role="row">
                    <th>{{ __('Employee Name') }}</th>
                    <td>{{ $employee->name }}</td>
                </tr>
                <tr>
                    <th>{{ __('Title ') }}</th>
                    <td>{{ !empty($loan->title) ? $loan->title : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Loan Option') }}</th>
                    <td>{{ $loan_options_text }}</td>
                </tr>
                <tr>
                    <th>{{ __('Amount') }}</th>
                    <td>{{ $loan->amount }}</td>
                </tr>
                <tr>
                    <th>{{ __('Installments') }}</th>
                    <td>{{ $loan->loan_installments }}</td>
                </tr>
                
                <tr>
                    <th>{{ __('Installment Per Month') }}</th>
                    <td>{{ ($loan->amount / $loan->loan_installments) }}</td>
                </tr>
                <tr>
                    <th>{{ __('Start Date') }}</th>
                    <td>{{ $loan->start_date }}</td>
                </tr>
                <tr>
                    <th>{{ __('End Date') }}</th>
                    <td>{{ $loan->end_date }}</td>
                </tr>
                <tr>
                    <th>{{ __('Reason') }}</th>
                    <td>{{ $loan->reason }}</td>
                </tr>
                <input type="hidden" value="{{ $loan->id }}" name="leave_id">
            </table>
        </div>
        
        <hr>
        <h5>Installment History</h5>
        <div class="col-12">
            <table class="table modal-table">
               <thead>
                    <th>No</th>
                    <th>Date</th>
                    <th style="text-align: center;">Amount</th>
                    <th style="text-align: center;">Status</th>
               </thead>
               <tbody>
                    @foreach ($loanInstallments as $row)
                         <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $row->installment_date }}</td>
                              <td style="text-align: right;">{{ $row->installment_amount }}</td>
                              <td style="text-align: right;">
                                   <span class="badge bg-{{ ($row->installment_status == 0 ? "danger" : "success") }}">{{ ($row->installment_status == 0 ? "No Paid" : "Paid") }}</span>
                              </td>
                         </tr>
                    @endforeach
               </tbody>
            </table>
        </div>
    </div>
</div>

{{ Form::close() }}