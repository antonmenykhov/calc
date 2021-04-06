<template>
<div id="app">
    <div class="razdel" v-for="razdel, u in data" :key="razdel.name">
        <p class="razdel-name">{{razdel.name}}</p>
        <div class="selects" v-for="select, i in razdel.arr" :key="select.name">
            <p v-if="select.type==='Варианты' || select.type==='Количество'" class="select-name">{{select.name}}</p>
            <p class="razdel-name"></p>
            <el-select  @change="changOn(u,i,selection[u+1+''+i])" v-if="select.type==='Варианты' && select.name !='Толщина утеплителя'" v-model="selection[u+1+''+i]" :placeholder="select.name">
                <el-option :label="opt.name" :value="index" v-for="opt, index in select.arr" :key="u+1+''+i+''+index"> </el-option>
            </el-select>
            <el-select v-model="selection[u+1+''+i]"  @change="changeUtepl(u,i,selection[u+1+''+i])" v-if="select.name ==='Толщина утеплителя'">
              <el-option label="150мм" value="0" ></el-option>
              <el-option label="100мм" value="2" ></el-option>
            </el-select>
            <el-input-number :min="0" @change="getPrice" :label="select.name" v-if="select.type==='Количество'" v-model="data[u].arr[i].arr[0].on" :placeholder="select.name"></el-input-number>

        </div>
    </div>
    <div class="bottom-panel">
        Общая стоимость: <span>{{price}}</span>
    </div>
</div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'app',
    data() {
        return {
            price: 0,
            data: [],
            selection: {}
        }
    },
    methods: {
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
            console.log( this.data[u].arr[j])
            this.data[u].arr[j].arr[selection].on = 1;
            this.data[u].arr[j].arr[selection+1].on = 1;
            this.getPrice();
        },
        
        getPrice() {
          this.price=0;
            for (let u = 0; u < this.data.length; u++) {
                if (this.data[u].arr != null) {
                    for (let j = 0; j < this.data[u].arr.length; j++) {
                        if (this.data[u].arr[j].arr != null) {
                            for (let z = 0; z < this.data[u].arr[j].arr.length; z++) {
                                let item = this.data[u].arr[j].arr[z];
                                this.price += item.on * item.price * item.number;
                            }
                        }
                    }
                }
            }
        }
    },

    mounted() {
        axios.get('http://test.menukhov.ru/newcalc/').then(response => { this.data = (response.data) })
    },
}
</script>

<style>
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
    justify-content: center;
    align-items: center;
}

.razdel {
    display: flex;
    flex-direction: column;
    align-items: center;

    flex-grow: 1;

    margin: 20px;
}

.selects:empty {
    display: none;
}

.razdel-name {
    font-weight: 600;
}

.razdel-name:nth-last-child(2) {
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
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 0 auto 60px auto;
}
</style>
