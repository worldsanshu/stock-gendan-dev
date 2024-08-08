import{a0 as t,a1 as e,a2 as a,b2 as s,al as i,ao as r,C as l,y as n,z as o,A as h,a9 as d,G as p,H as u,D as _,F as w,E as y,a8 as g,B as c}from"./index-V1zTiwCe.js";import{_ as m}from"./_plugin-vue_export-helper.BCo6x5W8.js";const k=m({name:"u-skeleton",mixins:[e,a,{props:{loading:{type:Boolean,default:()=>t.skeleton.loading},animate:{type:Boolean,default:()=>t.skeleton.animate},rows:{type:[String,Number],default:()=>t.skeleton.rows},rowsWidth:{type:[String,Number,Array],default:()=>t.skeleton.rowsWidth},rowsHeight:{type:[String,Number,Array],default:()=>t.skeleton.rowsHeight},title:{type:Boolean,default:()=>t.skeleton.title},titleWidth:{type:[String,Number],default:()=>t.skeleton.titleWidth},titleHeight:{type:[String,Number],default:()=>t.skeleton.titleHeight},avatar:{type:Boolean,default:()=>t.skeleton.avatar},avatarSize:{type:[String,Number],default:()=>t.skeleton.avatarSize},avatarShape:{type:String,default:()=>t.skeleton.avatarShape}}}],props:{isArticle:{type:Boolean,default:!0}},data:()=>({width:0}),watch:{loading(){this.getComponentWidth()}},computed:{rowsArray(){/%$/.test(this.rowsHeight);const t=[];for(let e=0;e<this.rows;e++){let a,r={};const l=e===this.rows-1;a=s.array(this.rowsWidth)?this.rowsWidth[e]||(l&&this.isArticle?"70%":"100%"):l&&this.isArticle?"70%":this.rowsWidth;const n=s.array(this.rowsHeight)?this.rowsHeight[e]||"18px":this.rowsHeight;r.marginTop=this.title||0!==e?this.title&&0===e?"20px":"12px":0,/%$/.test(a)?r.width=i(this.width*parseInt(a)/100):r.width=i(a),r.height=i(n),t.push(r)}return t},uTitleWidth(){let t=0;return t=/%$/.test(this.titleWidth)?i(this.width*parseInt(this.titleWidth)/100):i(this.titleWidth),i(t)}},mounted(){this.init()},methods:{addUnit:i,init(){this.getComponentWidth()},async setNvueAnimation(){},async getComponentWidth(){await r(20),this.$uGetRect(".u-skeleton__wrapper__content").then((t=>{this.width=t.width}))}}},[["render",function(t,e,a,s,i,r){const m=c;return l(),n(m,{class:"u-skeleton"},{default:o((()=>[t.loading?(l(),n(m,{key:0,class:"u-skeleton__wrapper",ref:"u-skeleton__wrapper",style:{display:"flex","flex-direction":"row"}},{default:o((()=>[t.avatar?(l(),n(m,{key:0,class:h(["u-skeleton__wrapper__avatar",[`u-skeleton__wrapper__avatar--${t.avatarShape}`,t.animate&&"animate"]]),style:d({height:r.addUnit(t.avatarSize),width:r.addUnit(t.avatarSize)})},null,8,["class","style"])):p("",!0),u(m,{class:"u-skeleton__wrapper__content",ref:"u-skeleton__wrapper__content",style:{flex:"1"}},{default:o((()=>[t.title?(l(),n(m,{key:0,class:h(["u-skeleton__wrapper__content__title",[t.animate&&"animate"]]),style:d({width:r.uTitleWidth,height:r.addUnit(t.titleHeight)})},null,8,["style","class"])):p("",!0),(l(!0),_(w,null,y(r.rowsArray,((e,a)=>(l(),n(m,{class:h(["u-skeleton__wrapper__content__rows",[t.animate&&"animate"]]),key:a,style:d({width:e.width,height:e.height,marginTop:e.marginTop})},null,8,["class","style"])))),128))])),_:1},512)])),_:1},512)):g(t.$slots,"default",{key:1},void 0,!0)])),_:3})}],["__scopeId","data-v-705c9879"]]);export{k as _};
