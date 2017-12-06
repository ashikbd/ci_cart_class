<?php
	/*
		A simple minicart template that is usually placed in header area. This one is based on bootstrap.
		In the footer or external js file, you can make a function like below and call it to load this html in right place.
		
		    function load_minicart() {
				$.ajax({
					url: "<?php echo base_url('common_ajax/minicart'); ?>",
					success: function (result) {
						$("#minicart").html(result); //suppose #minicart is a div located in header part
					}
				})
			}

			load_minicart(); //you can call this function whenever you need. eg. when you add a new item using ajax, you can call it again to update mini cart view
	
	*/
?>
<a type="button" class="btn-minicart dropdown-toggle" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false">
    <b><i class="fa fa-shopping-cart"></i></b> <span class="hidden-xs">Cart <?php echo count($items); ?> item(s) -</span> <strong><?php echo $total_price; ?> &euro;</strong> <span class="caret"></span>
</a>
<ul class="dropdown-menu dropdown-menu-right dropdown-cart">
    <?php
    if (count($items)) {
        foreach ($items as $item) {
            ?>
            <li>
                <span class="item">
                    <span class="item-left">

                        <span class="item-info">
                            <span><a href="<?php echo base_url('product/detail/' . $item['product_code']); ?>"><?php echo substr($item['product_name'], 0, 20); ?>...</a></span>
                            <span><?php echo lang('quantity');?>: <?php echo $item['product_qty']; ?> <?php echo lang('price');?>: <?php echo $item['product_price']; ?>&euro;</span>
                        </span>
                    </span>
                    <span class="item-right">
                        <button class="btn btn-xs btn-danger pull-right delete_cart_item" data-cnk="<?php echo $item['product_code'];?>">x</button>
                    </span>
                </span>
            </li>
            <?php
        }
    } else {
        echo '<li><span class="item">'.lang('no_item_in_cart').'</span></li>';
    }
    ?>
    <li class="divider"></li>
    <li><a class="text-center" href="<?php echo base_url('product/shopping_cart');?>"><?php echo lang('view_cart');?></a></li>
</ul>
