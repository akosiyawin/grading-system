@extends('layouts.app')

@push('styles')
    <style>
        body {
            margin-top: 20px;
            background: #eee;
        }

        .invoice {
            background: #fff;
            padding: 20px
        }

        .invoice-company {
            font-size: 20px
        }

        .invoice-header {
            margin: 0 -20px;
            background: #f0f3f4;
            padding: 20px;
        }

        .invoice-date,
        .invoice-from,
        .invoice-to {
            display: table-cell;
            width: 1%
        }

        .invoice-from,
        .invoice-to {
            padding-right: 10px
        }

        .invoice-date .date,
        .invoice-from strong,
        .invoice-to strong {
            font-size: 16px;
            font-weight: 600
        }

        .invoice-date {
            text-align: right;
            padding-left: 20px
        }

        .invoice-price {
            background: #f0f3f4;
            display: table;
            width: 100%
        }

        .invoice-price .invoice-price-left,
        .invoice-price .invoice-price-right {
            display: table-cell;
            padding: 20px;
            font-size: 20px;
            font-weight: 600;
            width: 75%;
            position: relative;
            vertical-align: middle
        }

        .invoice-price .invoice-price-left .sub-price {
            display: table-cell;
            vertical-align: middle;
            padding: 0 20px
        }

        .invoice-price small {
            font-size: 12px;
            font-weight: 400;
            display: block
        }

        .invoice-price .invoice-price-row {
            display: table;
            float: left
        }

        .invoice-price .invoice-price-right {
            width: 25%;
            background: #2d353c;
            color: #fff;
            font-size: 28px;
            text-align: right;
            vertical-align: bottom;
            font-weight: 300
        }

        .invoice-price .invoice-price-right small {
            display: block;
            opacity: .6;
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 12px
        }

        .invoice-footer {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px
        }

        .invoice-note {
            color: #999;
            margin-top: 80px;
            font-size: 85%
        }

        .invoice > div:not(.invoice-footer) {
            margin-bottom: 20px
        }

        .btn.btn-white, .btn.btn-white.disabled, .btn.btn-white.disabled:focus, .btn.btn-white.disabled:hover, .btn.btn-white[disabled], .btn.btn-white[disabled]:focus, .btn.btn-white[disabled]:hover {
            color: #2d353c;
            background: #fff;
            border-color: #d9dfe3;
        }

        .product-image {

        }

        @media screen {
            p.bodyText {
                font-family: verdana, arial, sans-serif;
            }
        }

        @media print {
            p.bodyText {
                font-family: georgia, times, serif;
            }
        }

        @media screen, print {
            p.bodyText {
                font-size: 10pt
            }
        }
    </style>

@endpush
<div class="container">
    <div class="col-md-12">
        <div class="invoice">
            <!-- begin invoice-company -->
            <div class="invoice-company text-inverse f-w-600">
            <span class="pull-right hidden-print">
            <a href="javascript:;" class="btn btn-sm btn-white m-b-10 p-l-5"><i
                        class="fa fa-file t-plus-1 text-danger fa-fw fa-lg"></i> Export as PDF</a>
            <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-white m-b-10 p-l-5"><i
                        class="fa fa-print t-plus-1 fa-fw fa-lg"></i> Print</a>
            </span>
                <div>
                    <div class="product-row row">
                        <div class="product-image col-md-1">
                            <img src="{{asset('assets/images/logo.png')}}">
                        </div>
                        <div class="product-text col-md">
                            <h4 class="text-success">Eastwoods Professional College of Science and Technology</h4>
                            <hr>
                            <p>We Educate, Develop and Inspire.</p>
                            {{--                            <div class="product-buttons">--}}
                            {{--                                <a href="http://bit.ly/2hqwtm2" target="_blank" id="Instant-Film" class="gift-guide-2017 shop btn">Shop Now</a>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                </div>
                <div class="text-center ">CERTIFICATION OF GRADES</div>
            </div>
            <!-- end invoice-company -->
            <!-- begin invoice-header -->
            <div class="invoice-header">
                <div class="invoice-from">
                    {{--                    <small>from</small>--}}
                    <address class="m-t-5 m-b-5">
                        {{--                        <strong class="text-inverse">Twitter, Inc.</strong><br>--}}
                        {{--                        Street Address:  <br>--}}
                        {{--                        City, Zip Code<br>--}}
                        {{--                        Phone: (123) 456-7890<br>--}}
                        {{--                        Fax: (123) 456-7890--}}
                        <div class="row">
                            <div class="col-md-3 text-bold">
                                <div>Name:</div>
                                <div>Student No.:</div>
                                <div>Course:</div>
                            </div>
                            <div class="col-md">
                               <div class="name"></div>
                                <div class="id_number"></div>
                                <div class="course"></div>
                            </div>
                        </div>

                    </address>
                </div>
                <div class="invoice-to">
                    <!--                    <small>to</small>-->
                    {{--                    <address class="m-t-5 m-b-5">--}}
                    {{--                        <strong class="text-inverse">Company Name</strong><br>--}}
                    {{--                        Street Address<br>--}}
                    {{--                        City, Zip Code<br>--}}
                    {{--                        Phone: (123) 456-7890<br>--}}
                    {{--                        Fax: (123) 456-7890--}}
                    {{--                        --}}
                    {{--                    </address>--}}
                    <div class="row">
                        <div class="col-md-3 text-bold">
                            <div>Name:</div>
                            <div>Student No.:</div>
                            <div>Course:</div>
                        </div>
                        <div class="col-md">
                            <div>asdasd</div>
                            <div>asdasd</div>
                            <div>asdasd</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="invoice-content">
                <!-- begin table-responsive -->
                <div class="semester_info text-right semester">

                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-invoice ">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th class="text-center" width="10%">Subject</th>
                            <th class="text-center" width="20%">Unit</th>
                            <th class="text-center" width="10%">Grade</th>
                            <th class="text-center" width="20%">Remarks</th>
                        </tr>
                        </thead>
                        <tbody class="data">
                        {{--                            <tr>--}}
                        {{--                                <td>--}}
                        {{--                                    <span class="text-inverse">Website design &amp; development</span><br>--}}
                        {{--                                    <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed id sagittis--}}
                        {{--                                        arcu.</small>--}}
                        {{--                                </td>--}}
                        {{--                                <td class="text-center">$50.00</td>--}}
                        {{--                                <td class="text-center">50</td>--}}
                        {{--                                <td class="text-center">$2,500.00</td>--}}
                        {{--                                <td class="text-center">$2,500.00</td>--}}
                        {{--                            </tr>--}}
                        </tbody>
                        <tfoot class="table-borderless footer">
                        <tr>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center units text-bold"></td>
                            <td class="text-center average text-bold">$2,500.00</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- end table-responsive -->
                <!-- begin invoice-price -->
            {{--                <div class="invoice-price">--}}
            {{--                    <div class="invoice-price-left">--}}
            {{--                        <div class="invoice-price-row">--}}
            {{--                            <div class="sub-price">--}}
            {{--                                <small>SUBTOTAL</small>--}}
            {{--                                <span class="text-inverse">$4,500.00</span>--}}
            {{--                            </div>--}}
            {{--                            <div class="sub-price">--}}
            {{--                                <i class="fa fa-plus text-muted"></i>--}}
            {{--                            </div>--}}
            {{--                            <div class="sub-price">--}}
            {{--                                <small>PAYPAL FEE (5.4%)</small>--}}
            {{--                                <span class="text-inverse">$108.00</span>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="invoice-price-right">--}}
            {{--                        <small>GWA</small> <span class="f-w-600">10</span>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            <!-- end invoice-price -->
            </div>
            {{--                <div class="invoice-date">--}}
            {{--                    <small>Invoice / July period</small>--}}
            {{--                    <div class="date text-inverse m-t-5">August 3,2012</div>--}}
            {{--                    <div class="invoice-detail">--}}
            {{--                        #0000123DSS<br>--}}
            {{--                        Services Product--}}
            {{--                    </div>--}}
            {{--                </div>--}}
        </div>
        <!-- end invoice-header -->
        <!-- begin invoice-content -->

        <!-- end invoice-content -->
        <!-- begin invoice-note -->
    {{--        <div class="invoice-note">--}}
    {{--            * Make all cheques payable to [Your Company Name]<br>--}}
    {{--            * Payment is due within 30 days<br>--}}
    {{--            * If you have any questions concerning this invoice, contact [Name, Phone Number, Email]--}}
    {{--        </div>--}}
    {{--        <!-- end invoice-note -->--}}
    {{--        <!-- begin invoice-footer -->--}}
    {{--        <div class="invoice-footer">--}}
    {{--            <p class="text-center m-b-5 f-w-600">--}}
    {{--                THANK YOU FOR YOUR BUSINESS--}}
    {{--            </p>--}}
    {{--            <p class="text-center">--}}
    {{--                <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i> matiasgallipoli.com</span>--}}
    {{--                <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> T:016-18192302</span>--}}
    {{--                <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> rtiemps@gmail.com</span>--}}
    {{--            </p>--}}
    {{--        </div>--}}
    <!-- end invoice-footer -->
    </div>
</div>
</div>
@section('content')

@endsection

@push('scripts')
    <script>
        async function ready() {
            let user_id = await axios.get('/api/user').then(r => {
                // console.log(r)
                // user_id = r.data.id;

                $.ajax({
                    url: '/api/print/' + r.data.id,
                    TYPE: 'POST',
                    success: function (r) {
                        let $tr = $('.data');
                        let html = "";

                        $(r[0]).each(function (r, v) {
                            html += '<tr class="text-center">'
                            html += '<td class="text-left">' + v.code + '</td>'
                            html += '<td>' + v.title + '</td>'
                            html += '<td>' + v.units + '</td>'
                            if (v.grade >= 98) {
                                html += '<td>1.0</td>'
                                html += '<td>Passed</td>'
                            } else if (v.grade >= 95) {
                                html += '<td>1.25</td>'
                                html += '<td>Passed</td>'
                            } else if (v.grade >= 92) {
                                html += '<td>1.50</td>'
                                html += '<td>Passed</td>'
                            } else if (v.grade >= 89) {
                                html += '<td>1.75</td>'
                                html += '<td>Passed</td>'
                            } else if (v.grade >= 86) {
                                html += '<td>2.0</td>'
                                html += '<td>Passed</td>'
                            } else if (v.grade >= 83) {
                                html += '<td>2.25</td>'
                                html += '<td>Passed</td>'
                            } else if (v.grade >= 80) {
                                html += '<td>2.50</td>'
                                html += '<td>Passed</td>'
                            } else if (v.grade >= 77) {
                                html += '<td>2.75</td>'
                                html += '<td>Passed</td>'
                            } else if (v.grade >= 75) {
                                html += '<td>3.0</td>'
                                html += '<td>Passed</td>'
                            } else {
                                html += '<td class="text-danger">5.0</td>'
                                html += '<td>Failed</td>'
                            }
                            html += '</tr>'
                            // console.log(v.grade);
                        });

                        $('.data').html(html);
                        $tr.html(html);
                    }, error: function (r) {
                        $('.data').html('');
                        $('.data').html('<tr>\n' +
                            '                <td colspan="99" class="text-center text-danger errormes">No records found</td>\n' +
                            '              </tr>');
                    }
                })

                $.ajax({
                    url: '/api/print/' + r.data.id,
                    TYPE: 'POST',
                    success:function(r){
                        $('.units').html(r.units);

                        $('.semester').html(r.activated_semester[0].title);
                        $('.average').html(r.average);
                        if (r.average)
                        //         html += '<td class="text-left">' + r.units + '</td>'
                        //
                        // $('.data').html(html);
                        // $tr.html(html);
                    }
                })
            })
        }

        ready();
    </script>
@endpush