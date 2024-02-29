
@if($template === 'Apec Homes HDMF')
        <div class="col-lg-12" style="margin-top:20px;">
            <form class="calculator-form" id="apec-homes">
                <div class="form-group total_contract_price">
                    <label for="total_contract_price">Total Contract Price</label><span class="required">*</span>
                    <input type="number" step=".01" name="total_contract_price" id="total_contract_price" class="form-control">
                </div>
                <div class="form-group discount">
                    <label for="discount">Discount</label><span class="required">*</span>
                    <input type="number" step=".01" name="discount" id="discount" class="form-control">
                </div>
                <div class="form-group reservation_fee">
                    <label for="reservation_fee">Reservation Fee</label><span class="required">*</span>
                    <input type="number" step=".01" name="reservation_fee" id="reservation_fee" class="form-control">
                </div>
                <div class="form-group loanable_amount">
                    <label for="loanable_amount">Loanable Amount</label><span class="required">*</span>
                    <input type="number" step=".01" name="loanable_amount" id="loanable_amount" class="form-control">
                </div>
                <div class="form-group months">
                    <label for="months">Months to pay</label><span class="required">*</span>
                    <input type="number" name="months" id="months" class="form-control">
                </div>
                <input type="reset" class="btn btn-danger btn-sm apec-calculator-reset" name="reset" value="Reset">
                <input type="submit" class="btn btn-primary btn-sm" name="submit" value="Calculate">
            </form>
            <p class="calculation-result"></p>
        </div>
    <script src="{{asset('js/calculator.js')}}"></script>

    @elseif($template === 'Hausland Bank Calculator')
    <div class="col-lg-12" style="margin-top:20px;">
        <form class="bank-computation-form">
            <div class="form-group total_contract_price">
                <label for="total_contract_price">Total Contract Price</label><span class="required">*</span>
                <input type="number" step=".01" name="total_contract_price" id="total_contract_price" class="form-control">
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group processing_fee">
                        <label for="processing_fee">Processing Fee</label><span class="required">*</span>
                        <input type="number" step=".01" name="processing_fee" id="processing_fee" class="form-control">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group reservation_fee">
                        <label for="reservation_fee">Reservation fee</label><span class="required">*</span>
                        <input type="number" step=".01" name="reservation_fee" id="reservation_fee" class="form-control">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group pf_months">
                        <label for="pf_months">PF Months</label><span class="required">*</span>
                        <select name="pf_months" id="pf_months" class="form-control">
                            @for($months = 1; $months <= 24;$months++)
                                <option value="{{$months}}">{{$months}} @if($months == 1) month @else months @endif</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group equity_percentage">
                        <label for="equity_percentage">Equity Percentage</label>
                        <input type="number" step=".01" name="equity_percentage" id="equity_percentage" class="form-control">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group exact_equity_amount">
                        <label for="exact_equity_amount">Equity Exact Amount</label><span class="required">*</span>

                        <div class="input-group mb-3">
                            <input type="number" step=".01" name="exact_equity_amount" id="exact_equity_amount" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text equity-amount-btn" data-toggle="tooltip" title="Click to compute"><i class="fas fa-calculator"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group equity_months">
                        <label for="equity_months">Equity Months</label><span class="required">*</span>
                        <select name="equity_months" id="equity_months" class="form-control">
                            @for($months = 1; $months <= 24;$months++)
                                <option value="{{$months}}">{{$months}} @if($months == 1) month @else months @endif</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>


            <div class="form-group loanable_amount">
                <label for="loanable_amount">Loanable Amount</label><span class="required">*</span>
                <div class="input-group mb-3">
                    <input type="number" step=".01" name="loanable_amount" id="loanable_amount" class="form-control">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text loanable-amount-btn" data-toggle="tooltip" title="Click to compute"><i class="fas fa-calculator"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group interest_rate">
                        <label for="interest_rate">Interest Rate</label><span class="required">*</span>
                        <input type="number" step=".001" name="interest_rate" id="interest_rate" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group years">
                        <label for="years">Years to pay</label><span class="required">*</span>
                        <select name="years" id="years" class="form-control">
                            @for($years = 1; $years <= 30;$years++)
                                <option value="{{$years}}">{{$years}} @if($years == 1) year @else years @endif</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <input type="reset" class="btn btn-danger btn-sm bank-calculator-reset" name="reset" value="Reset">
            <input type="submit" class="btn btn-primary btn-sm" name="submit" value="Calculate">
        </form>
        <p class="calculation-result"></p>
    </div>

    <script src="{{asset('js/hausland-bank-calculator.js')}}"></script>

    @elseif($template === 'Hausland Inhouse Calculator')
    <div class="col-lg-12" style="margin-top:20px;">
        <form class="bank-computation-form">
            <div class="form-group total_contract_price">
                <label for="total_contract_price">Total Contract Price</label><span class="required">*</span>
                <input type="number" step=".01" name="total_contract_price" id="total_contract_price" class="form-control">
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group processing_fee">
                        <label for="processing_fee">Processing Fee</label><span class="required">*</span>
                        <input type="number" step=".01" name="processing_fee" id="processing_fee" class="form-control">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group reservation_fee">
                        <label for="reservation_fee">Reservation fee</label><span class="required">*</span>
                        <input type="number" step=".01" name="reservation_fee" id="reservation_fee" class="form-control">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group pf_months">
                        <label for="pf_months">PF Months</label><span class="required">*</span>
                        <select name="pf_months" id="pf_months" class="form-control">
                            @for($months = 1; $months <= 24;$months++)
                                <option value="{{$months}}">{{$months}} @if($months == 1) month @else months @endif</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group equity_percentage">
                        <label for="equity_percentage">Equity Percentage</label>
                        <input type="number" step=".01" name="equity_percentage" id="equity_percentage" class="form-control">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group exact_equity_amount">
                        <label for="exact_equity_amount">Equity Exact Amount</label><span class="required">*</span>

                        <div class="input-group mb-3">
                            <input type="number" step=".01" name="exact_equity_amount" id="exact_equity_amount" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text equity-amount-btn" data-toggle="tooltip" title="Click to compute"><i class="fas fa-calculator"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group equity_months">
                        <label for="equity_months">Equity Months</label><span class="required">*</span>
                        <select name="equity_months" id="equity_months" class="form-control">
                            @for($months = 1; $months <= 24;$months++)
                                <option value="{{$months}}">{{$months}} @if($months == 1) month @else months @endif</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>


            <div class="form-group loanable_amount">
                <label for="loanable_amount">Loanable Amount</label><span class="required">*</span>
                <div class="input-group mb-3">
                    <input type="number" step=".01" name="loanable_amount" id="loanable_amount" class="form-control">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text loanable-amount-btn" data-toggle="tooltip" title="Click to compute"><i class="fas fa-calculator"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group years">
                        <label for="years">Years to pay</label><span class="required">*</span>
                        <select name="years" id="years" class="form-control">
                            <option value=""> -- Select Terms -- </option>
                            <option value="5">5 Years</option>
                            <option value="10">10 Years</option>
                            <option value="15">15 Years</option>
                        </select>
                    </div>
                </div>
            </div>

            <input type="reset" class="btn btn-danger btn-sm bank-calculator-reset" name="reset" value="Reset">
            <input type="submit" class="btn btn-primary btn-sm" name="submit" value="Calculate">
        </form>
        <p class="calculation-result"></p>
    </div>

    <script src="{{asset('js/hausland-inhouse-calculator.js')}}"></script>
    @elseif($template === 'Bank')
        <div class="col-lg-12" style="margin-top:20px;">
            <form class="bank-computation-form">
                <div class="form-group total_contract_price">
                    <label for="total_contract_price">Total Contract Price</label><span class="required">*</span>
                    <input type="number" step=".01" name="total_contract_price" id="total_contract_price" class="form-control">
                </div>
                <div class="form-group equity_percentage">
                    <label for="equity_percentage">Equity Percentage</label><span class="text-muted">(Optional)</span>
                    <input type="number" step=".01" name="equity_percentage" id="equity_percentage" class="form-control">
                </div>
                <div class="form-group exact_equity_amount">
                    <label for="exact_equity_amount">Equity Exact Amount</label><span class="required">*</span>

                    <div class="input-group mb-3">
                        <input type="number" step=".01" name="exact_equity_amount" id="exact_equity_amount" class="form-control">
                        <div class="input-group-append">
                            <button type="button" class="input-group-text equity-amount-btn" data-toggle="tooltip" title="Click to compute"><i class="fas fa-calculator"></i></button>
                        </div>
                    </div>
                </div>
                <div class="form-group loanable_amount">
                    <label for="loanable_amount">Loanable Amount</label><span class="required">*</span>
                    <div class="input-group mb-3">
                        <input type="number" step=".01" name="loanable_amount" id="loanable_amount" class="form-control">
                        <div class="input-group-append">
                            <button type="button" class="input-group-text loanable-amount-btn" data-toggle="tooltip" title="Click to compute"><i class="fas fa-calculator"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group interest_rate">
                            <label for="interest_rate">Interest Rate</label><span class="required">*</span>
                            <input type="number" step=".001" name="interest_rate" id="interest_rate" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group years">
                            <label for="years">Years to pay</label><span class="required">*</span>
                            <select name="years" id="years" class="form-control">
                                @for($years = 1; $years <= 30;$years++)
                                    <option value="{{$years}}">{{$years}} @if($years == 1) year @else years @endif</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <input type="reset" class="btn btn-danger btn-sm bank-calculator-reset" name="reset" value="Reset">
                <input type="submit" class="btn btn-primary btn-sm" name="submit" value="Calculate">
            </form>
            <p class="calculation-result"></p>
        </div>
        <script src="{{asset('js/bank-calculator.js')}}"></script>
@endif

