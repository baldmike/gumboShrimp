<template>
    <div>
      <table class="table" striped hover>
        <thead>
          <tr>
            <th>Item Name</th>
            <th>Image</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cartItem in cart" :key="cartItem.id">
            <td>{{ cartItem.item_name }}</td>
            <td><img :src="cartItem.image" alt="cart item" height="50"></td>
            <td>${{ cartItem.price }}</td>
            <td>
              <b-input-group>
                
                <b-form-input :value="cartItem.quantity" type="number" min="1" @input="updateQuantity(cartItem, $event.target.value)"></b-form-input>
                
              </b-input-group>
            </td>
            <td>${{ cartItem.totalPrice }}</td>
            <td>
              <a href="#" @click.prevent="removeFromCart(cartItem)" v-if="cartItem.quantity === 0">
                <span class="removeBtn">remove</span>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
  
      <div>
        <b-button block variant="primary" @click="goToCheckout">
          Go to Checkout - Current Total: ${{ totalPrice }}
        </b-button>
      </div>
    </div>
  </template>
  
  <script>
  import { mapGetters } from 'vuex';
  
  export default {
    data() {
      return {};
    },
  
    methods: {
      goToCheckout() {
        this.$router.push('/checkout');
      },
  
      removeFromCart(cartItem) {
        if (confirm('Are you sure you want to delete ' + cartItem.item_name + '?')) {
          this.$store.dispatch('removeFromCart', cartItem);
        }
      },
  
      addQuantityToCart(cartItem) {
        this.$store.dispatch('addQuantityToCart', cartItem);
      },
  
      subtractQuantityFromCart(cartItem) {
        if (cartItem.quantity > 1) {
          this.$store.dispatch('subtractQuantityFromCart', cartItem);
        } else {
          this.removeFromCart(cartItem);
        }
      },
  
      updateQuantity(cartItem, quantity) {
        const parsedQuantity = parseInt(quantity, 10);
        if (!isNaN(parsedQuantity)) {
          this.$store.dispatch('updateCartItemQuantity', { cartItem, quantity: parsedQuantity });
        }
      },
    },
  
    computed: {
      totalPrice() {
        let total = 0;
        for (let item of this.$store.state.cart) {
          total += item.totalPrice;
        }
        return total.toFixed(2);
      },
      ...mapGetters(['isAuthenticated', 'cart', 'cartCount']),
    },
  };
  </script>
  
  <style>
  .removeBtn {
    color: #dc3545;
    cursor: pointer;
    font-size: 0.9em;
  }
  
  table {
    margin-bottom: 1.5rem;
  }
  
  th {
    text-transform: capitalize;
  }
  
  img {
    max-height: 50px;
  }
  </style>
  