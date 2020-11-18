<!DOCTYPE html>
<html>
<head>
    <title>Faspay POST Example</title>
</head>

<body>
<style>
    .ex_input{
        display: block;
    }

    .ex_form_input{

    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance:textfield;
    }

    input[readonly="readonly"]
    {
        background-color:gray;
    }
</style>

<form action='checkout.php' method='POST'>

    Customer Name <input  class='ex_input' type='text' name='customer_name' value="Gamma" />
    Customer Address <input  class='ex_input' type='text' name='customer_address' value="Jakarta" />

    <br>
    <div class='ex_product'>
        <table border='2'>
            <tr>
            <tr>
                <td rowspan='2'><input checked="checked" type='radio' name='product' value='a:4:{i:0;s:7:"sample1";i:1;s:1:"1";i:2;s:6:"100000";i:3;s:2:"00";}' /></td>
                <td>Sample1</td>
            </tr>
            <tr>
                <td rowspan='2'>Harga : Rp 10.000,-</td>
            </tr>
            </tr>
            <tr>
            <tr>
                <td rowspan='2'><input type='radio' name='product' value='a:4:{i:0;s:7:"sample2";i:1;s:1:"1";i:2;s:6:"20000";i:3;s:2:"00";}' /></td>
                <td>Sample2</td>
            </tr>
            <tr>
                <td rowspan='2'>Harga : Rp 20.000,-</td>
            </tr>
            </tr>
            <tr>
            <tr>
                <td rowspan='2'><input type='radio' name='product' value='a:4:{i:0;s:7:"sample3";i:1;s:1:"1";i:2;s:6:"30000";i:3;s:2:"00";}' /></td>
                <td>Sample3</td>
            </tr>
            <tr>
                <td rowspan='2'>Harga : Rp 30.000,-</td>
            </tr>
            </tr>
        </table>
    </div>


    <div class='ex_form_input'>
        <h3>Choose Payment Channel</h3>
        <table>
            <tr><td><input type='radio' name='channel' value='302' />TCASH WEBCHECKOUT</td></tr>
            <tr><td><input type='radio' name='channel' value='303' />XL Tunai</td></tr>
            <tr><td><input type='radio' name='channel' value='304' />MYNT</td></tr>
            <tr><td><input type='radio' name='channel' value='305' />MANDIRI ECASH</td></tr>
            <tr><td><input type='radio' name='channel' value='307' />DOMPETKU</td></tr>
            <tr><td><input type='radio' name='channel' value='308' />BBM MONEY</td></tr>
            <tr><td><input type='radio' name='channel' value='400' />Mocash</td></tr>
            <tr><td><input type='radio' name='channel' value='401' />BRI EPAY</td></tr>
            <tr><td><input type='radio' name='channel' value='402' />Permata</td></tr>
            <tr><td><input type='radio' name='channel' value='405' />BCA Klikpay</td></tr>
            <tr><td><input type='radio' name='channel' value='406' />Mandiri Clickpay</td></tr>
            <tr><td><input type='radio' name='channel' value='407' />BII SMS</td></tr>
            <tr><td><input type='radio' name='channel' value='408' />BII VA</td></tr>
            <tr><td><input type='radio' name='channel' value='500' />Mandiri Credit Card</td></tr>
            <tr><td><input type='radio' name='channel' value='700' />CIMB CLICKS</td></tr>
            <tr><td><input type='radio' name='channel' value='701' />Danamon</td></tr>
            <tr><td><input type='radio' name='channel' value='702' />BCA VA</td></tr>
            <tr><td><input type='radio' name='channel' value='703' />Mandii VA</td></tr>
        </table>

        <div>
            <br/>
            Misc Fee
            <input type='number' readonly='readonly' name='miscfee' value='10000'/>
        </div>

        <input type='submit' />
    </div>
</form>
</body>

</html>