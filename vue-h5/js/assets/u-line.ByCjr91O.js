import{a1 as e,a2 as t,a3 as i,al as a,a4 as r,a5 as l,y as s,z as n,aa as o,C as d}from"./index-WgdLfFQD.js";import{_ as h}from"./_plugin-vue_export-helper.BCo6x5W8.js";const m=h({name:"u-line",mixins:[t,i,{props:{color:{type:String,default:()=>e.line.color},length:{type:[String,Number],default:()=>e.line.length},direction:{type:String,default:()=>e.line.direction},hairline:{type:Boolean,default:()=>e.line.hairline},margin:{type:[String,Number],default:()=>e.line.margin},dashed:{type:Boolean,default:()=>e.line.dashed}}}],computed:{lineStyle(){const e={};return e.margin=this.margin,"row"===this.direction?(e.borderBottomWidth="1px",e.borderBottomStyle=this.dashed?"dashed":"solid",e.width=a(this.length),this.hairline&&(e.transform="scaleY(0.5)")):(e.borderLeftWidth="1px",e.borderLeftStyle=this.dashed?"dashed":"solid",e.height=a(this.length),this.hairline&&(e.transform="scaleX(0.5)")),e.borderColor=this.color,r(e,l(this.customStyle))}}},[["render",function(e,t,i,a,r,l){const h=d;return s(),n(h,{class:"u-line",style:o([l.lineStyle])},null,8,["style"])}],["__scopeId","data-v-b1452dca"]]);export{m as _};
