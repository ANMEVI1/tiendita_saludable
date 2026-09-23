<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('tienditaStore', () => ({
        cart: [],
        isCartOpen: false,
        activeProduct: null,
        isModalOpen: false,
        selectedVariantId: null,

        init() {
            // Cargar carrito desde LocalStorage
            const savedCart = localStorage.getItem('tiendita_cart');
            if (savedCart) {
                this.cart = JSON.parse(savedCart);
            }

            // Guardar cambios en LocalStorage automáticamente
            this.$watch('cart', value => {
                localStorage.setItem('tiendita_cart', JSON.stringify(value));
            });
        },

        get cartTotalItems() {
            return this.cart.reduce((total, item) => total + item.quantity, 0);
        },

        get cartTotalCost() {
            return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0).toFixed(2);
        },

        openProductModal(productData) {
            this.activeProduct = productData;
            // Si tiene variantes, seleccionar la primera por defecto
            if (this.activeProduct.variantes && this.activeProduct.variantes.length > 0) {
                this.selectedVariantId = this.activeProduct.variantes[0].id;
            } else {
                this.selectedVariantId = null;
            }
            this.isModalOpen = true;
        },

        addToCart() {
            if (!this.activeProduct) return;

            let finalPrice = parseFloat(this.activeProduct.precio_regular) || 0;
            let variantName = '';

            if (this.selectedVariantId && this.activeProduct.variantes) {
                const variant = this.activeProduct.variantes.find(v => v.id == this.selectedVariantId);
                if (variant) {
                    finalPrice = parseFloat(variant.precio);
                    variantName = variant.nombre;
                }
            }

            // Identificador único (producto + variante)
            const cartItemId = `${this.activeProduct.id}_${this.selectedVariantId || 'base'}`;

            const existingItem = this.cart.find(item => item.cartItemId === cartItemId);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                this.cart.push({
                    cartItemId: cartItemId,
                    productId: this.activeProduct.id,
                    name: this.activeProduct.nombre,
                    variantName: variantName,
                    price: finalPrice,
                    image: this.activeProduct.imagen_url || '',
                    quantity: 1
                });
            }

            this.isModalOpen = false;
            this.isCartOpen = true;
        },

        removeFromCart(cartItemId) {
            this.cart = this.cart.filter(item => item.cartItemId !== cartItemId);
        },

        updateQuantity(cartItemId, delta) {
            const item = this.cart.find(i => i.cartItemId === cartItemId);
            if (item) {
                item.quantity += delta;
                if (item.quantity <= 0) {
                    this.removeFromCart(cartItemId);
                }
            }
        },

        checkout() {
            if (this.cart.length === 0) return;
            let text = "Hola La Tiendita Saludable, me gustaría realizar este pedido:\n\n";
            this.cart.forEach(item => {
                let v = item.variantName ? ` (${item.variantName})` : '';
                text += `- ${item.quantity}x ${item.name}${v} = S/ ${(item.price * item.quantity).toFixed(2)}\n`;
            });
            text += `\n*Total estimado: S/ ${this.cartTotalCost}*`;

            const phone = "<?= htmlspecialchars($configWeb['whatsapp_numero'] ?? '51984247684') ?>";
            const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }
    }));
});
</script>
