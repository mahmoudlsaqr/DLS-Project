<script type="text/javascript">
    $(function () {
        let service = $('.service_id');
        let branch = $('.branch_id');
        let doctor = $('.doctor_id');

        let price = $('#service_price');
        let tax = $('#service_tax');
        let discount = $('#service_discount');
        let discountValue = $('#service_discount_value');
        let count = $('#service_count');
        let net = $('#service_total_net');
        let total = $('#service_total');
        let workOrderDate = $('.work_order_date');

        service.on('change', function () {
            let serviceId = $(this).val();
            let doctorId = $('.doctor_id').val();
            let workOrderDateVal = workOrderDate.val();

            if (workOrderDateVal != '') {
                if (Number(serviceId) > 0) {
                    $.ajax({
                        url: `{{ route('get_doctor_service_details') }}`,
                        type: "GET",
                        data: {
                            date : workOrderDateVal,
                            serviceId : serviceId,
                            doctorId : doctorId,
                        },
                        success: function(data) {
                            count.val(`{{ $item['service_count'] ?? '1' }}`);
                            price.val(data['price']);
                            tax.val(data['tax']);
                            discount.val(data['discount']);
                            let discountVal = +discount.val() * +price.val() / 100;
                            discountValue.val(discountVal.toFixed(2).toString())

                            @if(isset($workOrder["service_price"]))
                                let netVal = +price.val() - +discountValue.val();
                                let factorVal = (+tax.val() * netVal) / 100;
                                let totalVal = factorVal + netVal;

                                net.val(netVal.toFixed(2).toString());
                                total.val(totalVal.toFixed(2).toString());
                            @else
                                mainFunction();
                            @endif

                        },
                        error: function(error) {
                            console.log(error["responseJSON"])
                            alert(`Error: ${error}`);
                        }
                    });
                }
            } else {
                alert("{{ __("Must choose the date first") }}");
                service.val(null).trigger("change");
            }
        });

        @if(isset($workOrder["service_price"]))
            mainFunction();
        @endif

        function mainFunction() {
            function calculationsCount() {
                if (+count.val() <= 0) {
                    alert("Count should be greater than 0");
                    count.val(1);
                }
                let priceVal = +count.val() * +price.val();
                let netVal;
                netVal = priceVal - (+discountValue.val() * +count.val());
                net.val(netVal.toFixed(2).toString());

                calculationsTax();
            }

            function calculationsPrice() {
                let priceVal = +count.val() * +price.val();
                let disVal = +discount.val() * +price.val() / 100;
                discountValue.val(disVal.toFixed(2).toString())
                var netVal = priceVal - (+discountValue.val() * +count.val());
                net.val(netVal.toFixed(2).toString());

                calculationsTax();
            }

            function calculationsTax() {
                let factorVal = (+tax.val() * +net.val()) / 100;
                let totalVal = factorVal + +net.val();
                total.val(totalVal.toFixed(2).toString());
            }

            count.on('keyup', function () {
                calculationsCount();
                calculationsTax();
            });

            price.on('keyup', function () {
                calculationsPrice();
                calculationsCount();
                calculationsTax();
            });

            tax.on('keyup', function () {
                calculationsCount();
                calculationsTax();
            });

            discount.on("keyup", () => {
                let disVal = +discount.val() * +price.val() / 100;
                discountValue.val(disVal.toFixed(2).toString())
                calculationsCount();
                calculationsTax();

            }) // End - keyup services discount percent.

            discountValue.on("keyup", () => {
                let dis = +discountValue.val() / +price.val() * 100;
                discount.val(dis.toFixed(2).toString())
                if (+discountValue.val() > +price.val()) {
                    alert(`Count should be less than ${+price.val()}`);
                    discountValue.val(0);
                    discount.val(0);
                }
                calculationsCount();

                calculationsTax();

            }) // End - keyup services discount value.
            calculationsCount();
        }
    });
</script>
