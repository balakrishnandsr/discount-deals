<?php
/**
 * Provide interface for adding promotion settings to workflow
 *
 * @package    Discount_Deals
 */

if (!defined('ABSPATH')) {
    exit;
}
/*
 * Variable declaration
 *
 * @var Discount_Deals_Admin $this Class variable.
 */
$workflow = $this->get_workflow();
?>
<table class="discount-deals-table">
    <tbody>
    <?php
    if ($workflow) {
        echo $workflow->get_discount()->load_promotion_fields();
    }
    ?>
    </tbody>
    <tfoot class="<?php if ($workflow) {
        echo "discount-deals-hidden";
    } ?>">
    <tr>
        <td>
            <p class="discount-deals-ph10"><?php esc_html_e("Promotion can be used to explain the available discounts to your customers.", "discount-deals"); ?></p>
        </td>
    </tr>
    </tfoot>
</table>
