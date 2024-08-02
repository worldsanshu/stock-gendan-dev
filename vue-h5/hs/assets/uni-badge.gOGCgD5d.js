import{y as t,z as e,A as s,a9 as i,B as a,aa as o,I as l,J as r,G as n,O as u,C as d}from"./index-ChRSEdyh.js";import{_ as p}from"./_plugin-vue_export-helper.BCo6x5W8.js";const f=p({name:"UniBadge",emits:["click"],props:{type:{type:String,default:"error"},inverted:{type:Boolean,default:!1},isDot:{type:Boolean,default:!1},maxNum:{type:Number,default:99},absolute:{type:String,default:""},offset:{type:Array,default:()=>[0,0]},text:{type:[String,Number],default:""},size:{type:String,default:"small"},customStyle:{type:Object,default:()=>({})}},data:()=>({}),computed:{width(){return 8*String(this.text).length+12},classNames(){const{inverted:t,type:e,size:s,absolute:i}=this;return[t?"uni-badge--"+e+"-inverted":"","uni-badge--"+e,"uni-badge--"+s,i?"uni-badge--absolute":""].join(" ")},positionStyle(){if(!this.absolute)return{};let t=this.width/2,e=10;this.isDot&&(t=5,e=5);const s=`${-t+this.offset[0]}px`,i=`${-e+this.offset[1]}px`,a={rightTop:{right:s,top:i},rightBottom:{right:s,bottom:i},leftBottom:{left:s,bottom:i},leftTop:{left:s,top:i}},o=a[this.absolute];return o||a.rightTop},dotStyle(){return this.isDot?{width:"10px",minWidth:"0",height:"10px",padding:"0",borderRadius:"10px"}:{}},displayValue(){const{isDot:t,text:e,maxNum:s}=this;return t?"":Number(e)>s?`${s}+`:e}},methods:{onClick(){this.$emit("click")}}},[["render",function(p,f,m,h,c,y){const g=u,b=d;return t(),e(b,{class:"uni-badge--x"},{default:s((()=>[i(p.$slots,"default",{},void 0,!0),m.text?(t(),e(g,{key:0,class:a([y.classNames,"uni-badge"]),style:o([y.positionStyle,m.customStyle,y.dotStyle]),onClick:f[0]||(f[0]=t=>y.onClick())},{default:s((()=>[l(r(y.displayValue),1)])),_:1},8,["class","style"])):n("",!0)])),_:3})}],["__scopeId","data-v-414d462c"]]);export{f as _};
