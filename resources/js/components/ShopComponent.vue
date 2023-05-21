<template>
    <b-container fluid class="container-box">
      <b-row>
        <b-col offset="4" lg="4" md="6" sm="8">
          <b-input-group class="mt-3">
            <template v-slot:append>
              <b-button class="btn">
                <li class="fa fa-search"></li>
              </b-button>
            </template>
            <b-form-input v-model="search" placeholder="Search for your item..."></b-form-input>
          </b-input-group>
        </b-col>
      </b-row>
      <br />
  
      <b-row id="card-body">
      <b-col lg="3" md="4" sm="6" v-for="item in filteredItems" :key="item.id">
        <b-card class="fadeIn" v-if="item.image" :img-src="item.image" img-alt="Item image" img-height="300" img-width="300" :title="item.item_name">
          <b-row class="text-center">
            <b-col>
              <b-card-text class="item-description">{{ item.description }}</b-card-text>
            </b-col>
          </b-row>

          <b-row class="text-center bottom">
            <b-col>
              <b-card-text v-b-popover.hover.top="'Item Price'" class="item-price">${{ item.price }}</b-card-text>
            </b-col>

            <b-col>
              <b-card-text v-b-popover.hover.top="'Number Available'" class="item-available">{{ item.number_available }}</b-card-text>
            </b-col>
          </b-row>

          <b-card-footer>
            <b-row>
              <b-col v-if="isAuthenticated">
                <b-button v-b-popover.hover.top="'Click here to edit item'" title="EDIT ITEM" @click.prevent="showUpdateItemModal(item)" class="btn btn-edit">
                  <i class="far fa-edit"></i>
                </b-button>
              </b-col>

              <b-col v-if="isAuthenticated">
                <b-button v-b-popover.hover.top="'Click here to delete item. You cannot undo this.'" @click.prevent="deleteItem(item.id, item.item_name)" class="btn btn-delete">
                  <i class="far fa-trash-alt"></i>
                </b-button>
              </b-col>

              <b-col v-if="!isAuthenticated">
                <b-button v-b-popover.hover.top="'Add to cart.'" :title="item.item_name" @click="addToCart(item)" class="btn btn-add-to-cart">
                  <i class="fas fa-shopping-cart"></i>
                </b-button>
              </b-col>
            </b-row>
          </b-card-footer>
        </b-card>
      </b-col>
    </b-row>
  
      <!-- View update Item Modal -->
      <b-modal id="editModal" hide-footer no-close-on-backdrop>
        <form>
          <div class="form-group">
            <label for="image" class="col-sm-2 control-label">Image</label>
            <b-form-file
              id="image"
              accept="image/*"
              v-model="updateItem.itemImage"
              placeholder="Choose an image..."
              @change="onImageChange"
            ></b-form-file>
          </div>
  
          <b-col cols="6" style="margin-top: 1rem;">
            <img v-if="url" :src="url" width="420" alt="Uploaded image">
          </b-col>
  
          <div class="form-group">
            <label for="name">Item</label>
            <input v-model="updateItem.itemName" type="text" class="form-control" id="name" />
          </div>
          <div class="form-group">
            <label for="price">Price</label>
            <input v-model="updateItem.itemPrice" type="text" class="form-control" id="price" />
          </div>
  
          <div class="form-group">
            <label for="description">Item Description</label>
            <input v-model="updateItem.itemDescription" type="text" class="form-control" id="description" />
          </div>
          <div class="form-group">
            <label for="number_available">Number Available</label>
            <input v-model="updateItem.numberAvailable" type="number" class="form-control" id="numberAvailable" />
          </div>
          <div class="form-group" v-if="updateItem.size">
            <label for="size">Size</label>
            <input v-model="updateItem.itemSize" type="text" class="form-control" id="size" />
          </div>
          <b-button class="mt-3" block @click.prevent="editItem">Edit Item</b-button>
        </form>
      </b-modal>
    </b-container>
  </template>
  
  <script>
  import { mapGetters, mapActions } from "vuex";
  
  export default {
    name: "shop",
    data() {
      return {
        search: "",
        items: [],
        updateItem: {
          itemName: "",
          itemDescription: "",
          itemImage: null,
          itemPrice: "",
          numberAvailable: "",
          itemSize: "",
          sent: false,
        },
        url: null,
      };
    },
  
    methods: {
      loadItems() {
        axios
          .get("/api/items")
          .then((response) => {
            this.items = response.data.data;
            console.log(this.items);
            console.log("Items have successfully loaded");
          })
          .catch((error) => {
            console.log("ERROR ON LOAD ITEMS. Error-->");
            console.log(error);
          });
      },
      showUpdateItemModal(item) {
        this.url = item.itemImage;
        this.updateItem.itemImage = item.itemImage;
        this.updateItem.itemName = item.item_name;
        this.updateItem.itemPrice = item.price;
        this.updateItem.numberAvailable = item.number_available;
        this.updateItem.itemDescription = item.description;
        this.updateItem.id = item.id;
  
        this.$bvModal.show("editModal");
      },
      editItem() {
        console.log(this.updateItem.id);
  
        let formData = new FormData();
  
        Object.keys(this.updateItem).forEach((key) => {
          formData.append(key, this.updateItem[key]);
        });
  
        formData.append("_method", "PUT");
  
        let self = this;
        axios
          .post("/api/items/" + this.updateItem.id, formData)
          .then((response) => {
            this.$bvModal.hide("editModal");
  
            setTimeout(
              this.$notify({
                group: "notifications",
                type: "success",
                title: "Success!",
                text: "Item successfully updated.",
                duration: 15000,
                width: "100%",
              }),
              9000
            );
            this.loadItems();
          })
          .catch((error) => {
            console.log(error);
          });
      },
      deleteItem(id, name) {
        if (confirm("Are you sure you want to delete " + name + "?")) {
          axios
            .delete("/api/items/" + id)
            .then(({ data }) => {
              setTimeout(
                this.$notify({
                  group: "notifications",
                  type: "success",
                  title: "Success!",
                  text: name + " successfully deleted.",
                  duration: 15000,
                  width: "100%",
                }),
                15000
              );
  
              this.loadItems();
            })
            .catch((error) => {
              console.log(error);
            });
        }
      },
      addToCart(item) {
        this.$notify({
          group: "notifications",
          type: "success",
          title: "Item Added",
          text: "Item added to cart.",
          duration: 8000,
          width: "100%",
        });
        this.$store.dispatch("addToCart", item);
      },
      updateProfile(e) {
        let file = e.target.files[0];
        let reader = new FileReader();
        if (file["size"] < 2111775) {
          reader.onloadend = (file) => {
            this.updateItem.image = reader.result;
            console.log(this.updateItem.image);
            console.log("IMAGE;");
          };
          reader.readAsDataURL(file);
        } else {
          this.$notify({
            group: "notifications",
            type: "warning",
            title: "Failure",
            text: "File too large",
            duration: 15000,
            width: "100%",
          });
        }
      },
      onImageChange(e) {
        const file = e.target.files[0];
        this.url = URL.createObjectURL(file);
        this.updateItem.image = file;
      },
    },
    computed: {
      totalPrice() {
        let total = 0;
        for (let item of this.$store.state.cart) {
          total += item.totalPrice;
          console.log(total);
        }
        return total.toFixed(2);
      },
      filteredItems() {
        let self = this;
  
        if (this.search) {
          return this.items.filter(
            (item) =>
              item.item_name.toLowerCase().indexOf(self.search.toLowerCase()) >=
              0
          );
        }
  
        return this.items;
      },
      ...mapGetters([
        "isAuthenticated",
        "currentUser",
        "cart",
        "cartCount",
      ]),
    },
    mounted() {
      this.loadItems();
      console.log("Shop Component mounted.");
    },
  };
  </script>
  
  <style scoped>
  .container-box {
    background-color: white;
    margin-top: 2em;
    padding-bottom: 2em;
  }
  
  .b-bottom {
    position: relative;
    top: 10;
    left: 0;
    right: 0;
    text-align: center;
  }
  
  .brandName {
    color: white;
  }
  
  .card-footer {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: auto;
    text-align: center;
  }
  
  .modal-backdrop {
    opacity: 0;
    transition: opacity 0.8s;
  }
  
  .modal-backdrop.in {
    opacity: 0.7;
  }
  
  .card-body {
    flex: 1 1 auto;
    height: 300px !important;
    padding: 1.25rem;
  }

  .item-description {
  font-size: 0.7em;
}

.item-price {
  font-weight: bold;
}

.item-available {
  color: #888;
}

.btn-edit,
.btn-delete,
.btn-add-to-cart {
  color: white;
}

.btn-edit {
  background-color: #3498db;
}

.btn-delete {
  background-color: #e74c3c;
}

.btn-add-to-cart {
  background-color: #27ae60;
}
  </style>
  