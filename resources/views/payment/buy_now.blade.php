<link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('js/bootstrap.js') }}" rel="stylesheet">

<style>
    /*
*
* ==========================================
* FOR DEMO PURPOSES
* ==========================================
*
*/

    body {
        background: #f5f5f5;
    }

    .rounded-lg {
        border-radius: 1rem;
    }

    .nav-pills .nav-link {
        color: #555;
    }

    .nav-pills .nav-link.active {
        color: #fff;
    }

</style>


<div class="container py-5">

    <!-- For demo purpose -->
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4">Bootstrap 4 credit card form</h1>
            <p class="lead mb-0">Easily build a well-structured credit card form using Bootstrap 4</p>
            <p class="lead">Snippet by <a href="https://bootstrapious.com/snippets">Bootstrapious</a></p>
        </div>
    </div>
    <!-- End -->


    <form class="form-horizontal" method="POST" action="https://www.sandbox.PayPal.com/cgi-bin/webscr ">
        <fieldset>
            <!-- Form Name -->
            <legend>Pay with PayPal</legend>
            <!-- Text input-->
            <div class="form-group">
                <input id="amount" name="amount" type="hidden" placeholder="amount to pay"
                       class="form-control input-md" value="{{ $invoice->balance }}">
            </div>
            <input type='hidden' name='business' value='sb-7j4hl606677@personal.example.com'>
            <input type='hidden' name='item_name' value='Camera'>
            <input type='hidden' name='item_number' value='CAM#N1'>
            <!--<input type='hidden' name='amount' value='10'>-->
            <input type='hidden' name='no_shipping' value='1'>
            <input type='hidden' name='currency_code' value='{{ $invoice->customer->currency->iso_code }}'>
            <input type='hidden' name='notify_url' value=''>
            <input type='hidden' name='cancel_return' value=''>
            <input type='hidden' name='return' value=''>
            <input type="hidden" name="cmd" value="_xclick">
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="submit"></label>
                <div class="col-md-4">
                    <button id="submit" name="pay_now" class="btn btn-primary">Pay With PayPal</button>
                </div>
            </div>
        </fieldset>
    </form>

</div>
