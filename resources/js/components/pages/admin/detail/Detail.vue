<template>
    <div class="content-wrapper">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Details</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <router-link to="/home/order">Home</router-link>
                                </li>
                                <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </section>
            <!-- /.container-fluid -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="card">
                        <div class="container-fliud">
                            <div class="wrapper row">
                                <div class="preview col-md-6">
                                    
                                    <div class="preview-pic tab-content">
                                    <div  class="tab-pane active" id="pic-1"><img  :src="this.img[0]" /></div>
                                    <div v-for="(imgs, index) in this.img" :key="index"  class="tab-pane" :id="'pic-'+ (index+1)"><img  :src="imgs" /></div>
                                    </div>
                                    <ul class="preview-thumbnail nav nav-tabs">
                                    <li class="active"><a data-target="#pic-1" data-toggle="tab"><img :src="this.img[0]" /></a></li>
                                    <li v-for="(imgs, index) in this.img" :key="index" ><a :data-target="'#pic-'+ (index+1)" data-toggle="tab"><img :src="imgs" /></a></li>
                                    </ul>
                                    
                                </div>
                                <div class="details col-md-6">
                                    <h3 class="product-title">{{datas[0].name}}</h3>
                                    <div class="rating">
                                        <div class="stars">
                                            <span class="">Rating: {{datas[0].rating}}</span>
                                            <span class="fa fa-star checked"></span>
                                            <!-- <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span> -->
                                        </div>
                                        <!-- <span class="review-no">41 reviews</span> -->
                                    </div>
                                    <p class="product-description">Description: {{datas[0].description}}</p>
                                    <p >Price: <span>{{datas[0].price}} Vnd</span></p>
                                    <p> Amount: {{datas[0].amount}}</p>
                                    <p v-if="datas[0].type == 0 "> Payment methods: Delivery</p>
                                    <p v-else > Payment methods: Credit</p>
                                    <p> Delivery price: {{datas[0].priceship}}</p>
                                    <p> Order date: {{datas[0].create}}</p>
                                    <h6>Total price: {{total(datas[0].price ,datas[0].priceship)}} Vnd</h6>
                                    <h6>Shop: {{datas[0].nameShop}}</h6>
                                    <h6 style="float:flex;">Customer: <span>{{datas[0].namecustomer}}</span></h6>
                                    <p>	&nbsp; 	&nbsp; 	&nbsp; - Phone: {{datas[0].username}}</p>
                                    <p>	&nbsp; 	&nbsp; 	&nbsp; - Email: {{datas[0].email}}</p>
                                    <p>	&nbsp; 	&nbsp; 	&nbsp; - Address: {{datas[0].addresscustomer}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<style scoped>

/*****************globals*************/
body {
  font-family: 'open sans';
  overflow-x: hidden; }

img {
  max-width: 100%;
  }
  /* .tab-pane img{
    max-width: 100%;
    max-height: 50%;
  } */
.preview {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column; }
  @media screen and (max-width: 996px) {
    .preview {
      margin-bottom: 20px; } }

.preview-pic {
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
      -ms-flex-positive: 1;
          flex-grow: 1; }

.preview-thumbnail.nav-tabs {
  border: none;
  margin-top: 15px; }
  .preview-thumbnail.nav-tabs li {
    width: 18%;
    margin-right: 2.5%; }
    .preview-thumbnail.nav-tabs li img {
      max-width: 100%;
      display: block; }
    .preview-thumbnail.nav-tabs li a {
      padding: 0;
      margin: 0; }
    .preview-thumbnail.nav-tabs li:last-of-type {
      margin-right: 0; }

.tab-content {
  overflow: hidden; }
  .tab-content img {
    width: 100%;
    -webkit-animation-name: opacity;
            animation-name: opacity;
    -webkit-animation-duration: .3s;
            animation-duration: .3s; }

.card {
  margin-top: 50px;
  background: #eee;
  padding: 3em;
  line-height: 1.5em; }

@media screen and (min-width: 997px) {
  .wrapper {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex; } }

.details {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column; }

.colors {
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
      -ms-flex-positive: 1;
          flex-grow: 1; }

.product-title, .price, .sizes, .colors {
  text-transform: UPPERCASE;
  font-weight: bold; }

.checked, .price span {
  color: #ff9f1a; }

.product-title, .rating, .product-description, .price, .vote, .sizes {
  margin-bottom: 15px; }

.product-title {
  margin-top: 0; }

.size {
  margin-right: 10px; }
  .size:first-of-type {
    margin-left: 40px; }

.color {
  display: inline-block;
  vertical-align: middle;
  margin-right: 10px;
  height: 2em;
  width: 2em;
  border-radius: 2px; }
  .color:first-of-type {
    margin-left: 20px; }

.add-to-cart, .like {
  background: #ff9f1a;
  padding: 1.2em 1.5em;
  border: none;
  text-transform: UPPERCASE;
  font-weight: bold;
  color: #fff;
  -webkit-transition: background .3s ease;
          transition: background .3s ease; }
  .add-to-cart:hover, .like:hover {
    background: #b36800;
    color: #fff; }

.not-available {
  text-align: center;
  line-height: 2em; }
  .not-available:before {
    font-family: fontawesome;
    content: "\f00d";
    color: #fff; }

.orange {
  background: #ff9f1a; }

.green {
  background: #85ad00; }

.blue {
  background: #0076ad; }

.tooltip-inner {
  padding: 1.3em; }

@-webkit-keyframes opacity {
  0% {
    opacity: 0;
    -webkit-transform: scale(3);
            transform: scale(3); }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1); } }

@keyframes opacity {
  0% {
    opacity: 0;
    -webkit-transform: scale(3);
            transform: scale(3); }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1); } }

/*# sourceMappingURL=style.css.map */
</style>
<script>

// add to component

export default {
    name: "Home",
    data() {
        return {
            searchQuery: null,
            datas: [
            ],
            img: [],
        };
    },
     created() {
        this.refreshData();
        // console.log(this.datas);
        console.log(Date()).format('DD-MM-YYYY');
    },
   
    methods: {
        refreshData() {
            let uri = `http://127.0.0.1:8000/api/post/detail/${localStorage.getItem('temp')}`;
            this.axios.post(uri).then(response => {
                this.datas = response.data;
                this.img = this.datas[0].listpath.split(',');
            });
        },  
         total(price,priceship){
            return price + priceship;
          },
    }
   
};
</script>
