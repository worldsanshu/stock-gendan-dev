import{a0 as e,a1 as t,a2 as i,ak as a,a3 as r,a4 as s,y as l,z as n,a9 as o,C as d}from"./index-v5N4FCa5.js";import{_ as h}from"./_plugin-vue_export-helper.BCo6x5W8.js";const m=h({name:"u-line",mixins:[t,i,{props:{color:{type:String,default:()=>e.line.color},length:{type:[String,Number],default:()=>e.line.length},direction:{type:String,default:()=>e.line.direction},hairline:{type:Boolean,default:()=>e.line.hairline},margin:{type:[String,Number],default:()=>e.line.margin},dashed:{type:Boolean,default:()=>e.line.dashed}}}],computed:{lineStyle(){const e={};return e.margin=this.margin,"row"===this.direction?(e.borderBottomWidth="1px",e.borderBottomStyle=this.dashed?"dashed":"solid",e.width=a(this.length),this.hairline&&(e.transform="scaleY(0.5)")):(e.borderLeftWidth="1px",e.borderLeftStyle=this.dashed?"dashed":"solid",e.height=a(this.length),this.hairline&&(e.transform="scaleX(0.5)")),e.borderColor=this.color,r(e,s(this.customStyle))}}},[["render",function(e,t,i,a,r,s){const h=d;return l(),n(h,{class:"u-line",style:o([s.lineStyle])},null,8,["style"])}],["__scopeId","data-v-b1452dca"]]);export{m as _};
