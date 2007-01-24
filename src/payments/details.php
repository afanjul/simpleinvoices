<?php
include('./include/include_main.php');
include("./include/validation.php");

/*validation code*/
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("b_name","Biller name");
jsFormValidationEnd();
jsEnd();
/*end validation code*/



#Info from DB print
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


#biller query
$sql = "select si_account_payments.*, si_customers.c_name, si_biller.b_name from si_account_payments, si_invoices, si_customers, si_biller  where ac_inv_id = si_invoices.inv_id and si_invoices.inv_customer_id = si_customers.c_id and si_invoices.inv_biller_id = si_biller.b_id and si_account_payments.ac_id='$_GET[inv_id]'";


$result = mysql_query($sql, $conn) or die(mysql_error());


while ($Array = mysql_fetch_array($result)) {
        $ac_idField = $Array['ac_id'];
        $ac_inv_idField = $Array['ac_inv_id'];
        $ac_amountField = $Array['ac_amount'];
        $ac_notesField = $Array['ac_notes'];
	$ac_payment_typeField = $Array['ac_payment_type'];
        $ac_dateField = date( $config['date_format'], strtotime( $Array['ac_date'] ) );
        $b_nameField = $Array['b_name'];
        $c_nameField = $Array['c_name'];

};

/*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
$inv_type_sql = "select * from si_invoices where inv_id = $ac_inv_idField";
$inv_type_result = mysql_query($inv_type_sql, $conn) or die(mysql_error());
while ($inv_typeArray = mysql_fetch_array($inv_type_result)) {
        $inv_typeField = $inv_typeArray['inv_type'];
};

$sql_invoice_desc = "select inv_ty_description from si_invoice_type where inv_ty_id = $inv_typeField";
$result_invoice_desc = mysql_query($sql_invoice_desc, $conn) or die(mysql_error());

while ($invoice_descArray = mysql_fetch_array($result_invoice_desc)) {
	   $inv_ty_descriptionField = $invoice_descArray['inv_ty_description'];
};
/*Code to get the Invoice preference - so can link from this screen back to the invoice - END*/

                #Payment type section
                $payment_type_description = "select pt_description from si_payment_types where pt_id = $ac_payment_typeField";
                $result_payment_type_description = mysql_query($payment_type_description, $conn) or die(mysql_error());

                while ($Array_pt = mysql_fetch_array($result_payment_type_description) ) {
                                $payment_type_descriptionField = $Array_pt['pt_description'];
                };



$display_block =  "
<table align=center>
	<tr>
		<td class='details_screen'>$map_table_payment_id</td><td>$ac_idField</td>
	</tr>
	<tr>
		<td class='details_screen'>$map_table_payment_invoice_id</td><td><a href='print_quick_view.php?submit=$ac_inv_idField&action=view&invoice_style=$inv_ty_descriptionField''>$ac_inv_idField</a></td>
	</tr>
	<tr>
		<td class='details_screen'>$map_table_amount</td><td>$ac_amountField</td>
	</tr>
	<tr>
		<td class='details_screen'>$map_table_date</td><td>$ac_dateField</td>
	</tr>
	<tr>
		<td class='details_screen'>$map_table_biller</td><td>$b_nameField</td>
	</tr>
	<tr>
		<td class='details_screen'>$map_table_customer</td><td>$c_nameField</td>
	</tr>
	<tr>
		<td class='details_screen'>$map_table_payment_type</td><td>$payment_type_descriptionField</td>
	</tr>
        <tr>
                <td class='details_screen'>$map_table_notes</td><td>$ac_notesField
        </tr>



</table>
";


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simple Invoices - Payment details</title>
<?php include('./config/config.php'); ?>
</head>
<body>
<b><?php echo $map_page_header; ?></b>
<hr></hr>
<div id="browser">

<?php echo $display_block; ?>
<hr></hr>
	<form>
		<input type=button value='Back' onCLick='history.back()'>
	</form>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->