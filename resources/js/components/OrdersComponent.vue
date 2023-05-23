<template>
    <b-container fluid class="container-box">
      <b-row id="card-body">
        <b-col lg="3" md="4" sm="6" v-for="order in orders" :key="order.id">
          <b-card class="fadeIn" style="padding: 1.5rem;">
            <b-card-text class="order-id">Order Id: {{ order.id }}</b-card-text>
            <b-card-text class="order-info">Name: {{ order.name }}</b-card-text>
            <b-card-text class="order-info">Email: {{ order.email }}</b-card-text>
            <b-card-text class="order-info">Address: {{ order.address }}</b-card-text>
            <b-card-text class="order-info">City: {{ order.city }}</b-card-text>
            <b-card-text class="order-info">State: {{ order.state }}</b-card-text>
            <b-card-text class="order-info">Zip: {{ order.zip }}</b-card-text>
            <b-row v-for="(item, id) in order.order_item" :key="id" class="text-center">
              <b-col>
                {{ item["quantity"] }} -- {{ item["item_name"] }}
              </b-col>
            </b-row>
            <b-card-text class="order-amount">Amount: {{ order.amount }}</b-card-text>
            <b-button class="fulfill-button" @click.prevent="fulfillOrder(order.id)">Fulfill</b-button>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </template>

<script>
    import { mapGetters, mapActions } from "vuex";
    
    export default {
        data(){
            return {
                orders:[],
                items:[]
            }
        },

        methods: {
            loadOrders(){
                axios.get('/api/orders')
                .then(response =>{
                    this.orders = response.data.data;
                    console.log(this.orders);
                })
                .catch(error =>{
                    console.log(error);
                });
            },
            loadItems(){
                axios.get('/api/items')
                .then(response =>{
                    this.items = response.data.data;
                    console.log(this.items);
                    console.log("Items have successfully loaded");
                })
                .catch(error => {
                    console.log("ERROR ON LOAD ITEMS. Error-->");
                    console.log(error);
                }); 
            },
            fulfillOrder(id){
                axios.delete('/api/orders/' + id).then(response =>{
                    this.$router.go(0);
                    console.log('DELETED SUCCESSFULLY'); 
                });

                console.log("Order Deleted");
            }
        },
        mounted() {
            this.loadOrders();
            this.loadItems();
            console.log('shop component mounted');
        }
    }
</script>

<style scoped>
.container-box {
  background-color: white;
  margin-top: 2em;
  padding-bottom: 2em;
}

.b-card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  transition: 0.3s;
  text-align: center;
}

.b-card:hover {
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}

.order-id {
  font-size: 1em;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.order-info {
  font-size: 0.9em;
  margin-bottom: 0.3rem;
}

.order-amount {
  font-weight: bold;
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
}

.fulfill-button {
  background-color: #3498db;
  color: white;
  margin-top: 0.5rem;
}
</style>