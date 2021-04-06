<template>
  <div id="app">
   <div class="razdel" v-for="razdel, u in data" :key="razdel.name">
     {{razdel.name}}
     <div class="selects" v-for="select, i in razdel.arr" :key="select.name" >
       
       <el-select @change="data[u].arr[i].arr[selection[u+1+''+i]].on=1" v-if="select.type==='Варианты'" v-model="selection[u+1+''+i]" :placeholder="select.name">
         <el-option :label="opt.name" :value="index" v-for="opt, index in select.arr" :key="u+1+''+i+''+index"> </el-option>
       </el-select>
       <el-input-number :label="select.name" v-if="select.type==='Количество'" v-model="selection[select.name]" :placeholder="select.name"></el-input-number>
       
     </div>
   </div>
   {{selection}}
   {{data}}

  </div>
</template>

<script>

import axios from 'axios';
export default {
  name: 'app',
  data() {
    return {
      data: [],
      selection: {}
    }
  },
  
 
  mounted() {
    axios.get('http://test.menukhov.ru/newcalc/').then(response => {this.data = (response.data)} )
  },
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
