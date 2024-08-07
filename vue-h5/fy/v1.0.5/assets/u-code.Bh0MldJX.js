import{a0 as e,a1 as t,a2 as s,a_ as i,a$ as n,b0 as a,C as h,y as r,B as c}from"./index-hU8OKPmH.js";import{_ as u}from"./_plugin-vue_export-helper.BCo6x5W8.js";const o=u({name:"u-code",mixins:[t,s,{props:{seconds:{type:[String,Number],default:()=>e.code.seconds},startText:{type:String,default:()=>e.code.startText},changeText:{type:String,default:()=>e.code.changeText},endText:{type:String,default:()=>e.code.endText},keepRunning:{type:Boolean,default:()=>e.code.keepRunning},uniqueKey:{type:String,default:()=>e.code.uniqueKey}}}],data(){return{secNum:this.seconds,timer:null,canGetCode:!0}},mounted(){this.checkKeepRunning()},watch:{seconds:{immediate:!0,handler(e){this.secNum=e}}},emits:["start","end","change"],methods:{checkKeepRunning(){let e=Number(i(this.uniqueKey+"_$uCountDownTimestamp"));if(!e)return this.changeEvent(this.startText);let t=Math.floor(+new Date/1e3);this.keepRunning&&e&&e>t?(this.secNum=e-t,n(this.uniqueKey+"_$uCountDownTimestamp"),this.start()):this.changeEvent(this.startText)},start(){this.timer&&(clearInterval(this.timer),this.timer=null),this.$emit("start"),this.canGetCode=!1,this.changeEvent(this.changeText.replace(/x|X/,this.secNum)),this.timer=setInterval((()=>{--this.secNum?this.changeEvent(this.changeText.replace(/x|X/,this.secNum)):(clearInterval(this.timer),this.timer=null,this.changeEvent(this.endText),this.secNum=this.seconds,this.$emit("end"),this.canGetCode=!0)}),1e3),this.setTimeToStorage()},reset(){this.canGetCode=!0,clearInterval(this.timer),this.secNum=this.seconds,this.changeEvent(this.endText)},changeEvent(e){this.$emit("change",e)},setTimeToStorage(){if(this.keepRunning&&this.timer&&this.secNum>0&&this.secNum<this.seconds){let e=Math.floor(+new Date/1e3);a({key:this.uniqueKey+"_$uCountDownTimestamp",data:e+Number(this.secNum)})}}},beforeUnmount(){this.setTimeToStorage(),clearTimeout(this.timer),this.timer=null}},[["render",function(e,t,s,i,n,a){const u=c;return h(),r(u,{class:"u-code"})}],["__scopeId","data-v-d37c7793"]]);export{o as _};
