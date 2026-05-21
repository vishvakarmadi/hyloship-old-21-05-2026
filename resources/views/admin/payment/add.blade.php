@extends('admin.admin_layouts')
@section('admin_content')

<div class="container-fluid">
    <!-- Page header section  -->
    
<div class="row clearfix">
    <div class="col-12">
        <div class="card pt-30">
            <div class="card-header">
                <h5 class="card-title mb-0">Wallet Balance: Rs
                    <span>{{ Auth::guard('admin')->user()->wallet_blc }}</span><a data-action="collapse"
                        class="float-right toggle-icon"><i class="fa fa-minus"></i></a></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <strong>Quick Recharge:</strong>
                            <div class="btn-group " role="group" aria-label="Quick Recharge Buttons">
                                <input type="button" class="btn btn-primary" value="1000"
                                    onclick="calculation(this.value, 'amount')">
                                <input type="button" class="btn btn-primary" value="2000"
                                    onclick="calculation(this.value, 'amount')">
                                <input type="button" class="btn btn-primary" value="5000"
                                    onclick="calculation(this.value, 'amount')">
                                <input type="button" class="btn btn-primary" value="10000"
                                    onclick="calculation(this.value, 'amount')">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coupon_code" id="label_coupon_code" class="text-left">Enter Amount:</label>
                            <input type="number" name="amount" class="form-control phone-number"
                                placeholder="Minimum recharge amount Rs 500" id="amount" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coupon_code" id="label_coupon_code" class="text-left">Apply Coupon code
                                (Optional)</label>
                            <div class="input-group">
                                <input type="text" name="coupon_code" id="coupon_code" class="form-control"
                                    placeholder="Coupon code">
                                <div class="input-group-append" id="apply_button">
                                    <button type="button" class="btn btn-primary" id="apply_coupon"
                                        onclick="applyCoupon()">Apply</button>
                                </div>
                            </div>
                            <div id="coupon_msg" class="mt-2" style="font-weight: bold;"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button"  class="btn btn-primary col-lg-3" id="model">Recharge</button>
                    </div><br>
                    <div id="codpayment"></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="rechargeForm" action="{{route('admin.payment.add_money')}}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">You Are Just One Step Away</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Name</label><span class="required"> *</span>
                            <input class="form-control" type="text" name="name" required
                                value="{{ Auth::guard('admin')->user()->name }}" readonly>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-control-label">Email</label><span class="required"> *</span>
                            <input class="form-control" type="email" name="email" required
                                value="{{ Auth::guard('admin')->user()->email }}" readonly>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Phone Number</label><span class="required"> *</span>
                            <input class="form-control" type="Number" name="number"
                                value="{{ Auth::guard('admin')->user()->mobile }}" required readonly>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="coupon_code" id="label_coupon_code" class="text-left">Select Payment Mode:</label>
                                <select name="payment" class="form-control" id="paymentMethod">
                                    <option value="1">Online Payment</option>
                                    <!--<option value="2">COD Remittance</option>-->
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Amount to be Paid</label><span class="required"> *</span>
                            <input class="form-control" type="Number" name="modal_amount" id="modal_amount" required readonly>
                            <input type="hidden" name="total_amount" id="total_amount" required>
                        </div>
                    </div>
                    <input type="hidden" name="razorpay_response" id="razorpay_response" value="">
                    <input type="hidden" name="coupon_code" id="coupon_code_applied" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="payButton">Proceed To Pay</button>
                </div>
            </div>

    
        </form>
    </div>
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php $currentuser = Auth::guard('admin')->user()->id; ?>
<script>
    document.getElementById('payButton').onclick = function () {
        if($("select[name=payment]").val() == 1){
            var amount = (document.getElementById('amount').value) + '.00';
            var razorpayOptions = {
                key: "{{ env('RAZORPAY_KEY') }}",
                amount: amount *100, 
                name: "{{ Auth::guard('admin')->user()->name }}",
                description: "Razorpay payment",
                image: "/public/avatar.png",
                prefill: {
                    name: "{{ Auth::guard('admin')->user()->name }}",
                    email: "{{ Auth::guard('admin')->user()->email }}"
                },
                theme: {
                    color: "#ff7529"
                },
                handler: function (response) {
                    console.log('Payment successful:', response);
                    document.getElementById('razorpay_response').value = JSON.stringify(response);
                    document.getElementById('rechargeForm').submit(); 
                },
                prefill: {
                    name: "{{ Auth::guard('admin')->user()->name }}",
                    email: "{{ Auth::guard('admin')->user()->email }}"
                }
            };
            var rzp = new Razorpay(razorpayOptions);
            rzp.open();
        } else {
            const paymentMethod = $('#paymentMethod').val();
            const amount = $('#modal_amount').val();

            $.ajax({
                url: "{{ route('admin.payment.cod') }}",
                type: "get",
                data: {
                    paymentMethod: paymentMethod,
                    amount: amount,
                },
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                success: function(response) {
                    location.reload();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
        
    };
</script>


<script>
function calculation(val, amount) {
    $('#amount').val(val);
    $('#total_amount').val(val);
}

$('#amount').on('input', function(){
    $('#total_amount').val($(this).val());
});

function applyCoupon() {
    var couponCode = document.getElementById('coupon_code').value;
    var amount = document.getElementById('amount').value;

    // Check if the couponCode is empty
    if (amount.trim() === '') {
        toastr.error('Enter amount first..!!');
        return; // Exit the function if couponCode is empty
    }
    
    // Check if the couponCode is empty
    if (couponCode.trim() === '') {
        toastr.error('Enter coupon first..!!');
        return; 
    }

    $.ajax({
        url: "{{ route('admin.coupons.validate') }}",
        type: "get",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            coupon_code: couponCode,
            amount: amount,
        },
        success: function(response) {
            if (response.success) {
                var amountInput = $('#amount');
                $('#coupon_code_applied').val(couponCode);
                
                if (response.is_cashback) {
                    var msg = 'Coupon ' + couponCode + ' applied! ₹' + response.cashback_amount + ' cashback will be added after payment.';
                    toastr.success(msg);
                    $('#coupon_msg').html('<span class="text-success">' + msg + '</span>');
                    // Don't reduce the amount
                } else {
                    amountInput.val(response.amount);
                    $('#modal_amount').val(response.amount);
                    var msg = 'Discount applied. New amount: ₹' + response.amount;
                    toastr.success(msg);
                    $('#coupon_msg').html('<span class="text-success">' + msg + '</span>');
                }
                
                $('#apply_button').hide();
                $('#coupon_code').prop('readonly', true);
            } else {
                var errorMsg = response.message || 'Invalid coupon or expired';
                toastr.error(errorMsg);
                $('#coupon_msg').html('<span class="text-danger">' + errorMsg + '</span>');
            }
        },
        error: function() {
            toastr.error('Error while validating coupon');
        }
    });
}


$(document).ready(function() {
    $("#model").on("click", function(event) {
        var amountInput = document.getElementById('amount');
        if (amountInput.value.trim() !== '') {
            if(amountInput.value.trim() < 500){
               toastr.error("Minimum recharge amount is 500..!!"); 
            }else{
                $('#exampleModalCenter').modal('show'); 
                $('#modal_amount').val(amountInput.value);
            }
        } else {
            toastr.error("Enter money first..!!");
        }
    });
});

</script>

@endsection