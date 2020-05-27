
@if($template === 'Apec Homes')
        <div class="col-lg-12" style="margin-top:20px;">
            <form class="calculator-form" id="apec-homes">
                <div class="form-group total_contract_price">
                    <label for="total_contract_price">Total Contract Price</label>
                    <input type="text" name="total_contract_price" id="total_contract_price" class="form-control">
                </div>
                <div class="form-group discount">
                    <label for="discount">Discount</label>
                    <input type="text" name="discount" id="discount" class="form-control">
                </div>
                <div class="form-group reservation_fee">
                    <label for="reservation_fee">Reservation Fee</label>
                    <input type="text" name="reservation_fee" id="reservation_fee" class="form-control">
                </div>
                <div class="form-group loanable_amount">
                    <label for="loanable_amount">Loanable Amount</label>
                    <input type="text" name="loanable_amount" id="loanable_amount" class="form-control">
                </div>
                <div class="form-group months">
                    <label for="months">Months to pay</label>
                    <input type="text" name="months" id="months" class="form-control">
                </div>
                <input type="reset" class="btn btn-danger btn-sm apec-calculator-reset" name="reset" value="Reset">
                <input type="submit" class="btn btn-primary btn-sm" name="submit" value="Calculate">
            </form>
            <p class="calculation-result"></p>
        </div>
    <script src="{{asset('js/calculator.js')}}"></script>
@endif

