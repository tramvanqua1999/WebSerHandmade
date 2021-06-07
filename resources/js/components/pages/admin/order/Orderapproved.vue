<template>
    <div class="content-wrapper">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">List Order</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <router-link to="/home/order">Home</router-link>
                                </li>
                                <li class="breadcrumb-item active">Order</li>
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
                    <div class="white-box">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="white-box">
                                    
                                    <br /> <br />
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            DataTable All order approved
                                                        </h3>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <div class="form-group has-search">
                                                            <span class="fa fa-search form-control-feedback"></span>
                                                            <input type="text" class="form-control" v-model="searchQuery" >
                                                        </div>
                                                        <div>
                                                        <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                                        <thead>
                                                            <tr role="row"><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">id</th>
                                                            <th class="sorting_desc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" aria-sort="descending">Name</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Image</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Price</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Amount</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Delivery price</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Total price</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Payment</th>
                                                           </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="data in  resultQuery" role="row" class="odd" :key="data.id">
                                                        <td>{{data.id}}</td>
                                                        <td class="sorting_1">{{data.name}}</td>
                                                        <td class="text-center"><img :src="data.listpath.split(',')[0]" width="50px" height="50px"  class="rounded " alt="..."></td>
                                                        <td>{{data.price}}</td>
                                                         <td>{{data.amount}}</td>
                                                        <td>{{data.priceship}}</td>
                                                        <td>{{total(data.amount,data.price,data.priceship)}}</td>
                                                        <td v-if="data.type == 0">Delivery</td>
                                                        <td v-else>Credit</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    .has-search .form-control {
        padding-left: 2.375rem;
        box-shadow: 0 0 10px #719ECE;
    }
  
    .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #aaa;
}
</style>
<script>

// add to component

export default {
    name: "Home",
    data() {
        return {
            searchQuery: null,
            datas: [],
        };
    },
    computed: {
            resultQuery(){
            if(this.searchQuery){
                 if(this.filterUsersByName(this.datas).length !=0){
                    return this.filterUsersByName(this.datas)
                 }
            }else{
                return this.datas;
            }
            }
        },
     created() {
        this.interval = setInterval(this.refreshData, 1500);
    },
    beforeDestroy() {
        clearInterval(this.interval);
    },
    methods: {
        refreshData() {
            let uri = `http://127.0.0.1:8000/api/post/approved`;
            this.axios.get(uri).then(response => {
                this.datas = response.data;
                this.datas.listpath.split(',')[0];
            });
        },  
        filterUsersByName: function(datas){
            return datas.filter((item)=>{
                return this.searchQuery.toLowerCase().split(' ').every(v => item.name.toLowerCase().includes(v))
        })
        },
        total(amount,price,priceship){
            console.log(amount * price +priceship);
          return  amount * price +priceship;
        },
    }
};
</script>
