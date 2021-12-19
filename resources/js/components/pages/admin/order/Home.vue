<template>
    <div class="content-wrapper">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Danh sách đơn hàng</h1>
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
                                                            Dữ liệu danh sách đơn hàng
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
                                                            <th class="sorting_desc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" aria-sort="descending">Tên</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Hình</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Giá</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Số lượng</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Giá vận chuyển</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">tổng tiền</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Thanh toán</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Xóa</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Xem</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Xác nhận</th></tr>
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
                                                        <td v-if="data.type == 0">Vận chuyển</td>
                                                        <td v-else>Thẻ VNPAY</td>
                                                         <td v-if="data.type === 0" class=""><button class="btn btn-danger" @click.prevent="cancel(data.id)"><i class="fas fa-trash-alt"></i></button></td>
                                                        <td v-else class=""><button class="btn btn-default"> <i class="fas fa-trash-alt"></i></button></td>
                                                        <td class="sorting_1"><button class="btn btn-success"  @click.prevent="detail(data.id)"><i class="fas fa-eye"></i></button></td>
                                                        <td class="sorting_1"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-check"></i></button></td>
                                                        <td>
                                                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLongTitle">Xác nhận đơn hàng?</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            Đơn hàng sẽ không được hoàn trả bạn có muốn chắc đặt nó?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng!</button>
                                                                            <button type="button" class="btn btn-primary" data-dismiss="modal" @click.prevent="cofirm(data.id)">Đặt hàng</button>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
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
            let uri = `http://127.0.0.1:8000/api/post/order`;
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

        detail(id){
            this.$store.commit('STORE_SUCCESS', id)
                this.$router.push({
                    path: "/home/detail",
                    params: {
                        studentId: id
                    }
                }); 
        },
       
        cancel(id){
             let uri = `http://127.0.0.1:8000/api/post/cancel/${id}`;
            this.axios.post(uri).then(response => {
                this.datas.splice(
                    this.datas.map(item => item.id).indexOf(id),
                    1
                );
                this.$toast.open({
                    message: response.data,
                    type: "success",
                    duration: 1000,
                    dismissible: true
                });
            }); 
        },
        total(amount,price,priceship){
            console.log(amount * price +priceship);
          return  amount * price +priceship;
        },
        cofirm(id) {
            let uri = `http://127.0.0.1:8000/api/post/cofirm/${id}`;
            this.axios.post(uri).then(response => {
                this.datas.splice(
                    this.datas.map(item => item.id).indexOf(id),
                    1
                );
                this.$toast.open({
                    message: response.data,
                    type: "success",
                    duration: 1000,
                    dismissible: true
                });
            });
        },
    }
};
</script>
