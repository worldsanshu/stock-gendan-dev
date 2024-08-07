import{a0 as e,a1 as t,a2 as a,a4 as o,al as l,br as s,x as c,y as r,z as i,A as n,H as u,a9 as h,a8 as d,I as p,J as y,G as f,B as m,bo as g,O as b,C as _,bs as k}from"./index-_qVRbLMl.js";import{_ as C}from"./u-icon.WTlR_o2P.js";import{r as S}from"./uni-app.es.CwLmRD9h.js";import{_ as w}from"./_plugin-vue_export-helper.BCo6x5W8.js";const x=w({name:"u-search",mixins:[t,a,{props:{shape:{type:String,default:()=>e.search.shape},bgColor:{type:String,default:()=>e.search.bgColor},placeholder:{type:String,default:()=>e.search.placeholder},clearabled:{type:Boolean,default:()=>e.search.clearabled},focus:{type:Boolean,default:()=>e.search.focus},showAction:{type:Boolean,default:()=>e.search.showAction},actionStyle:{type:Object,default:()=>e.search.actionStyle},actionText:{type:String,default:()=>e.search.actionText},inputAlign:{type:String,default:()=>e.search.inputAlign},inputStyle:{type:Object,default:()=>e.search.inputStyle},disabled:{type:Boolean,default:()=>e.search.disabled},borderColor:{type:String,default:()=>e.search.borderColor},searchIconColor:{type:String,default:()=>e.search.searchIconColor},color:{type:String,default:()=>e.search.color},placeholderColor:{type:String,default:()=>e.search.placeholderColor},searchIcon:{type:String,default:()=>e.search.searchIcon},searchIconSize:{type:[Number,String],default:()=>e.search.searchIconSize},margin:{type:String,default:()=>e.search.margin},animation:{type:Boolean,default:()=>e.search.animation},modelValue:{type:String,default:()=>e.search.value},value:{type:String,default:()=>e.search.value},maxlength:{type:[String,Number],default:()=>e.search.maxlength},height:{type:[String,Number],default:()=>e.search.height},label:{type:[String,Number,null],default:()=>e.search.label},adjustPosition:{type:Boolean,default:()=>!0},autoBlur:{type:Boolean,default:()=>!1}}}],data(){return{keyword:"",showClear:!1,show:!1,focused:this.focus}},watch:{keyword(e){this.$emit("update:modelValue",e),this.$emit("change",e)},modelValue:{immediate:!0,handler(e){this.keyword=e}}},computed:{showActionBtn(){return!this.animation&&this.showAction}},emits:["clear","search","custom","focus","blur","click","clickIcon","update:modelValue","change"],methods:{addStyle:o,addUnit:l,inputChange(e){this.keyword=e.detail.value},clear(){this.keyword="",this.$nextTick((()=>{this.$emit("clear")}))},search(e){this.$emit("search",e.detail.value);try{s()}catch(t){}},custom(){this.$emit("custom",this.keyword);try{s()}catch(e){}},getFocus(){this.focused=!0,this.animation&&this.showAction&&(this.show=!0),this.$emit("focus",this.keyword)},blur(){setTimeout((()=>{this.focused=!1}),100),this.show=!1,this.$emit("blur",this.keyword)},clickHandler(){this.disabled&&this.$emit("click")},clickIcon(e){this.$emit("clickIcon",this.keyword);try{s()}catch(t){}}}},[["render",function(e,t,a,o,l,s){const w=b,x=S(c("u-icon"),C),I=_,B=k;return r(),i(I,{class:"u-search",onClick:s.clickHandler,style:h([{margin:e.margin},s.addStyle(e.customStyle)])},{default:n((()=>[u(I,{class:"u-search__content",style:h({backgroundColor:e.bgColor,borderRadius:"round"==e.shape?"100px":"4px",borderColor:e.borderColor})},{default:n((()=>[e.$slots.label||null!==e.label?d(e.$slots,"label",{key:0},(()=>[u(w,{class:"u-search__content__label"},{default:n((()=>[p(y(e.label),1)])),_:1})]),!0):f("",!0),u(I,{class:"u-search__content__icon"},{default:n((()=>[u(x,{onClick:s.clickIcon,size:e.searchIconSize,name:e.searchIcon,color:e.searchIconColor?e.searchIconColor:e.color},null,8,["onClick","size","name","color"])])),_:1}),u(B,{"confirm-type":"search",onBlur:s.blur,value:l.keyword,onConfirm:s.search,onInput:s.inputChange,disabled:e.disabled,onFocus:s.getFocus,focus:e.focus,maxlength:e.maxlength,"adjust-position":e.adjustPosition,"auto-blur":e.autoBlur,"placeholder-class":"u-search__content__input--placeholder",placeholder:e.placeholder,"placeholder-style":`color: ${e.placeholderColor}`,class:"u-search__content__input",type:"text",style:h([{textAlign:e.inputAlign,color:e.color,backgroundColor:e.bgColor,height:s.addUnit(e.height)},e.inputStyle])},null,8,["onBlur","value","onConfirm","onInput","disabled","onFocus","focus","maxlength","adjust-position","auto-blur","placeholder","placeholder-style","style"]),l.keyword&&e.clearabled&&l.focused?(r(),i(I,{key:1,class:"u-search__content__icon u-search__content__close",onClick:s.clear},{default:n((()=>[u(x,{name:"close",size:"11",color:"#ffffff",customStyle:"line-height: 12px"})])),_:1},8,["onClick"])):f("",!0)])),_:3},8,["style"]),u(w,{style:h([e.actionStyle]),class:m(["u-search__action",[(s.showActionBtn||l.show)&&"u-search__action--active"]]),onClick:g(s.custom,["stop","prevent"])},{default:n((()=>[p(y(e.actionText),1)])),_:1},8,["style","class","onClick"])])),_:3},8,["onClick","style"])}],["__scopeId","data-v-dd711d5b"]]);export{x as _};
