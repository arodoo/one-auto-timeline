<style>
    .cart-dropdown {
        display: none;
        position: absolute;
        top: 40px;
        right: 0;
        width: 250px;
        background: #fff;
        
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        padding: 15px;
        z-index: 1000;
    }

    .cart-content {
        text-align: center;
    }

    .checkout-btn {
        display: block;
        background-color: #007bff;
        color: white;
        text-align: center;
        padding: 8px;
        border-radius: 5px;
        text-decoration: none;
        margin-top: 10px;
    }

    .checkout-btn:hover {
        background-color: #0056b3;
    }

    .cart-item {
        border-bottom: 1px solid rgb(56, 56, 56) !important;
        margin-bottom: 10px;
        padding-bottom: 10px;
    }

    .cart-item:last-child {
        border-bottom: none;
    }
</style>



<div class="cart-dropdown" id="cart-dropdown">
    <div class="cart-content">
        Mon panier
        <ul id="cart-items">
            <li>Chargement des articles...</li>
        </ul>
        <a href="/Paiement" class="checkout-btn">Accéder au paiement</a>
    </div>
</div>