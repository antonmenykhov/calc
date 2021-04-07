<template>
<div id="app">
    <div class="first-select">
        <el-radio-group @change="chBk" v-model="bk">
          <el-radio-button v-for="ch, i in rawData" :key="i"   :label="i" >{{ch.name}}</el-radio-button>
        </el-radio-group>
       
    </div>
    <div class="container">
    <div class="razdel" v-for="razdel, u in data" :key="razdel.name">
        
        <div class="selects" v-for="select, i in razdel.arr" :key="i">
            <p v-if="select.type==='Варианты' || select.type==='Количество' || select.type==='Опции'" class="select-name">{{select.name}}</p>
            
            <el-select @change="changOn(u,i,selection[u+1+''+i])" v-if="select.type==='Варианты' && select.name !='Толщина утеплителя'" v-model="selection[u+1+''+i]" :placeholder="select.name">
                <el-option :label="opt.name" :value="index" v-for="opt, index in select.arr" :key="u+1+''+i+''+index"> </el-option>
            </el-select>
            <el-select v-model="selection[u+1+''+i]" @change="changeUtepl(u,i,selection[u+1+''+i])" v-if="select.name ==='Толщина утеплителя'">
                <el-option label="150мм" value="0"></el-option>
                <el-option label="100мм" value="2"></el-option>
            </el-select>
            <el-input-number :min="0" @change="getPrice" :label="select.name" v-if="select.type==='Количество'" v-model="data[u].arr[i].arr[0].number" :placeholder="select.name"></el-input-number>
            <el-checkbox border v-if="select.type==='Опции'" @change="chOpt(u,i)" v-model="checktion[u+1+''+i]" :label="select.name"></el-checkbox>
        </div>
    </div>
    </div>
   
    <div class="bottom-panel">
       <p>Общая стоимость: <span>{{price}}</span></p> 
        <el-button id="materials" @click="popup=true">Материалы</el-button>
    </div>
    <el-dialog width="80%" :visible.sync="popup" title="Перечень материалов">
       <div v-html="table"></div>
    </el-dialog>
</div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'app',
    data() {
        return {
            bk: null,
            price: 0,
            data: [],
            rawData: [],
            selection: {},
            checktion: {},
            table: ``,
            popup: false
        }
    },
    methods: {
        chBk(){
            for (var obj in this.checktion){
                this.checktion[obj] = 0;
            }
            this.selection = {};
            this.data = JSON.parse(JSON.stringify(this.rawData[this.bk].arr))     ; 
            this.price = 0;
            this.table=``;
        },
        chOpt(u, j) {
            for (let i = 0; i < this.data[u].arr[j].arr.length; i++) {
                if (this.data[u].arr[j].arr[i].on == 0) {
                    this.data[u].arr[j].arr[i].on = 1;
                } else{
                  this.data[u].arr[j].arr[i].on = 0;
                }
                this.getPrice();
            }
        },
        changOn(u, j, selection) {
            for (let i = 0; i < this.data[u].arr[j].arr.length; i++) {
                this.data[u].arr[j].arr[i].on = 0;
            }
            this.data[u].arr[j].arr[selection].on = 1;
            this.getPrice();
        },
        changeUtepl(u, j, selection) {
            for (let i = 0; i < this.data[u].arr[j].arr.length; i++) {
                this.data[u].arr[j].arr[i].on = 0;
            }
            this.data[u].arr[j].arr[selection].on = 1;
            this.data[u].arr[j].arr[selection * 1 + 1].on = 1;
            this.getPrice();
        },

        getPrice() {
            this.price = 0;
             this.table = `<table>
                  <tr>
                    <th>Название</th>
                    <th>Размер</th>
                    <th>Ед.изм.</th>
                    <th>Количество</th>
                    <th>Цена за шт.</th>
                    <th>Сумма</th>
                  </tr>`
            for (let u = 0; u < this.data.length; u++) {
                if (this.data[u].arr != null) {
                   this.table += `<tr>
                                  <td class="table-razdel" colspan=6>${this.data[u].name}</td>
                                  </tr>`
                    for (let j = 0; j < this.data[u].arr.length; j++) {
                        if (this.data[u].arr[j].arr != null) {
                            for (let z = 0; z < this.data[u].arr[j].arr.length; z++) {
                                let item = this.data[u].arr[j].arr[z];
                                let summa = item.on * item.price * item.number
                                this.price += summa;
                                if (item.on >= 1 && item.number>0) {
                                    this.table += `<tr>
                                  <td>${item.name}</td>
                                  <td>${item.razmer}</td>
                                  <td>${item.ed}</td>
                                  <td>${item.number}</td>
                                  <td>${item.price}</td>
                                  <td>${summa}</td>
                                  </tr>`
                                }
                            }
                        }
                    }
                }
            }
        }
    },

    mounted() {
        axios.get('http://test.menukhov.ru/newcalc/').then(response => { this.rawData = (response.data) })
    },
}
</script>

<style>
.container{
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    justify-content: center;
}
table{
  width: 100%;
}
th{
  background: #3e3e3e;
  color: white;
  padding: 4px;
}

td:nth-child(1){
  text-align: left;
  padding: 3px;
}
tr:nth-child(odd){
  background: rgb(228, 228, 228);
}
.table-razdel{
  background: rgb(167, 167, 167)!important;
  color: white;
  text-align: center!important;
}
.bottom-panel {
    position: fixed;
    bottom: 0;
    width: 100%;
    height: 100px;
    background: #3e3e3e;
    color: white;
    font-size: 30px;
    text-align: center;
    display: flex;
    justify-content: space-around;
    align-items: center;
    z-index: 2;
}

.razdel {
    display: flex;
    flex-direction: column;
    align-items: center;

    
}

.selects{
  margin: 0 20px;
}

.selects:empty {
    display: none;
}

.razdel-name {
    font-weight: 600;
}

.razdel:empty {
    display: none;
}

#app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    position: relative;
    display: flex;
    justify-content: center;
    flex-direction: column;
    flex-wrap: wrap;
    
    margin: 0 auto 100px auto;

}
body{
    margin: 0!important
}
</style>
