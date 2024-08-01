import{a1 as t,a2 as e,a3 as a,b8 as s,al as i,ao as r,y as n,z as l,A as o,B as h,aa as d,G as p,H as u,D as w,F as _,E as y,a9 as g,C as m}from"./index-BWJszhAQ.js";import{_ as c}from"./_plugin-vue_export-helper.BCo6x5W8.js";const f=c({name:"u-skeleton",mixins:[e,a,{props:{loading:{type:Boolean,default:()=>t.skeleton.loading},animate:{type:Boolean,default:()=>t.skeleton.animate},rows:{type:[String,Number],default:()=>t.skeleton.rows},rowsWidth:{type:[String,Number,Array],default:()=>t.skeleton.rowsWidth},rowsHeight:{type:[String,Number,Array],default:()=>t.skeleton.rowsHeight},title:{type:Boolean,default:()=>t.skeleton.title},titleWidth:{type:[String,Number],default:()=>t.skeleton.titleWidth},titleHeight:{type:[String,Number],default:()=>t.skeleton.titleHeight},avatar:{type:Boolean,default:()=>t.skeleton.avatar},avatarSize:{type:[String,Number],default:()=>t.skeleton.avatarSize},avatarShape:{type:String,default:()=>t.skeleton.avatarShape}}}],props:{isArticle:{type:Boolean,default:!0}},data:()=>({width:0}),watch:{loading(){this.getComponentWidth()}},computed:{rowsArray(){/%$/.test(this.rowsHeight);const t=[];for(let e=0;e<this.rows;e++){let a,r={};const n=e===this.rows-1;a=s.array(this.rowsWidth)?this.rowsWidth[e]||(n&&this.isArticle?"70%":"100%"):n&&this.isArticle?"70%":this.rowsWidth;const l=s.array(this.rowsHeight)?this.rowsHeight[e]||"18px":this.rowsHeight;r.marginTop=this.title||0!==e?this.title&&0===e?"20px":"12px":0,/%$/.test(a)?r.width=i(this.width*parseInt(a)/100):r.width=i(a),r.height=i(l),t.push(r)}return t},uTitleWidth(){let t=0;return t=/%$/.test(this.titleWidth)?i(this.width*parseInt(this.titleWidth)/100):i(this.titleWidth),i(t)}},mounted(){this.init()},methods:{addUnit:i,init(){this.getComponentWidth()},async setNvueAnimation(){},async getComponentWidth(){await r(20),this.$uGetRect(".u-skeleton__wrapper__content").then((t=>{this.width=t.width}))}}},[["render",function(t,e,a,s,i,r){const c=m;return n(),l(c,{class:"u-skeleton"},{default:o((()=>[t.loading?(n(),l(c,{key:0,class:"u-skeleton__wrapper",ref:"u-skeleton__wrapper",style:{display:"flex","flex-direction":"row"}},{default:o((()=>[t.avatar?(n(),l(c,{key:0,class:h(["u-skeleton__wrapper__avatar",[`u-skeleton__wrapper__avatar--${t.avatarShape}`,t.animate&&"animate"]]),style:d({height:r.addUnit(t.avatarSize),width:r.addUnit(t.avatarSize)})},null,8,["class","style"])):p("",!0),u(c,{class:"u-skeleton__wrapper__content",ref:"u-skeleton__wrapper__content",style:{flex:"1"}},{default:o((()=>[(n(!0),w(_,null,y(r.rowsArray,((e,a)=>(n(),l(c,{class:h(["u-skeleton__wrapper__content__rows",[t.animate&&"animate"]]),key:a,style:d({width:e.width,height:e.height,marginTop:e.marginTop})},null,8,["class","style"])))),128))])),_:1},512)])),_:1},512)):g(t.$slots,"default",{key:1},void 0,!0)])),_:3})}],["__scopeId","data-v-43601306"]]);export{f as _};
