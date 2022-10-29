<script src="https://pay.fondy.eu/static_common/v1/checkout/ipsp.js"></script>
<script>
    let button = $ipsp.get('button');
    button.setMerchantId(1475006);
    button.setAmount('', 'USD');
    button.setHost('pay.fondy.eu');
    button.addField({
        label: 'user_id',
        name: 'user_id',
        value: "{{auth()->id()}}",
        hidden:true,
        readonly:true,
    });

    document.querySelectorAll('.replenish-link').forEach(link => {
        link.href = button.getUrl();
    })
</script>
