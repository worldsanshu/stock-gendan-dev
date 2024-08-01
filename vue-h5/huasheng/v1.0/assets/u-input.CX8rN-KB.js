import{_ as e}from"./u-icon.D7SiMdEc.js";import{a1 as t,a2 as n,a3 as o,bh as i,a4 as a,a5 as l,al as r,ao as s,x as u,y as p,z as d,A as c,H as f,a9 as h,G as y,aa as m,B as g,C as b,b7 as S}from"./index-BrsO_Ji3.js";import{r as x}from"./uni-app.es.ouLKK4z2.js";import{_}from"./_plugin-vue_export-helper.BCo6x5W8.js";const C=_({name:"u-input",mixins:[n,o,{props:{modelValue:{type:[String,Number],default:()=>t.input.value},type:{type:String,default:()=>t.input.type},fixed:{type:Boolean,default:()=>t.input.fixed},disabled:{type:Boolean,default:()=>t.input.disabled},disabledColor:{type:String,default:()=>t.input.disabledColor},clearable:{type:Boolean,default:()=>t.input.clearable},password:{type:Boolean,default:()=>t.input.password},maxlength:{type:[String,Number],default:()=>t.input.maxlength},placeholder:{type:String,default:()=>t.input.placeholder},placeholderClass:{type:String,default:()=>t.input.placeholderClass},placeholderStyle:{type:[String,Object],default:()=>t.input.placeholderStyle},showWordLimit:{type:Boolean,default:()=>t.input.showWordLimit},confirmType:{type:String,default:()=>t.input.confirmType},confirmHold:{type:Boolean,default:()=>t.input.confirmHold},holdKeyboard:{type:Boolean,default:()=>t.input.holdKeyboard},focus:{type:Boolean,default:()=>t.input.focus},autoBlur:{type:Boolean,default:()=>t.input.autoBlur},disableDefaultPadding:{type:Boolean,default:()=>t.input.disableDefaultPadding},cursor:{type:[String,Number],default:()=>t.input.cursor},cursorSpacing:{type:[String,Number],default:()=>t.input.cursorSpacing},selectionStart:{type:[String,Number],default:()=>t.input.selectionStart},selectionEnd:{type:[String,Number],default:()=>t.input.selectionEnd},adjustPosition:{type:Boolean,default:()=>t.input.adjustPosition},inputAlign:{type:String,default:()=>t.input.inputAlign},fontSize:{type:[String,Number],default:()=>t.input.fontSize},color:{type:String,default:()=>t.input.color},prefixIcon:{type:String,default:()=>t.input.prefixIcon},prefixIconStyle:{type:[String,Object],default:()=>t.input.prefixIconStyle},suffixIcon:{type:String,default:()=>t.input.suffixIcon},suffixIconStyle:{type:[String,Object],default:()=>t.input.suffixIconStyle},border:{type:String,default:()=>t.input.border},readonly:{type:Boolean,default:()=>t.input.readonly},shape:{type:String,default:()=>t.input.shape},formatter:{type:[Function,null],default:()=>t.input.formatter},ignoreCompositionEvent:{type:Boolean,default:!0}}}],data:()=>({clearInput:!1,innerValue:"",focused:!1,firstChange:!0,changeFromInner:!1,innerFormatter:e=>e}),watch:{modelValue:{immediate:!0,handler(e,t){this.innerValue=e,!1===this.firstChange&&!1===this.changeFromInner?this.valueChange():this.firstChange||i(this,"change"),this.firstChange=!1,this.changeFromInner=!1}}},computed:{isShowClear(){const{clearable:e,readonly:t,focused:n,innerValue:o}=this;return!!e&&!t&&!!n&&""!==o},inputClass(){let e=[],{border:t,disabled:n,shape:o}=this;return"surround"===t&&(e=e.concat(["u-border","u-input--radius"])),e.push(`u-input--${o}`),"bottom"===t&&(e=e.concat(["u-border-bottom","u-input--no-radius"])),e.join(" ")},wrapperStyle(){const e={};return this.disabled&&(e.backgroundColor=this.disabledColor),"none"===this.border?e.padding="0":(e.paddingTop="6px",e.paddingBottom="6px",e.paddingLeft="9px",e.paddingRight="9px"),a(e,l(this.customStyle))},inputStyle(){return{color:this.color,fontSize:r(this.fontSize),textAlign:this.inputAlign}}},emits:["update:modelValue","focus","blur","change","confirm","clear","keyboardheightchange"],methods:{setFormatter(e){this.innerFormatter=e},onInput(e){let{value:t=""}=e.detail||{};const n=(this.formatter||this.innerFormatter)(t);this.innerValue=t,this.$nextTick((()=>{this.innerValue=n,this.valueChange()}))},onBlur(e){this.$emit("blur",e.detail.value),s(150).then((()=>{this.focused=!1})),i(this,"blur")},onFocus(e){this.focused=!0,this.$emit("focus")},onConfirm(e){this.$emit("confirm",this.innerValue)},onkeyboardheightchange(e){this.$emit("keyboardheightchange",e)},valueChange(){this.clearInput&&(this.innerValue="",this.clearInput=!1);const e=this.innerValue;this.$nextTick((()=>{this.$emit("update:modelValue",e),this.changeFromInner=!0,this.$emit("change",e),i(this,"change")}))},onClear(){this.clearInput=!0,this.innerValue="",this.$nextTick((()=>{this.valueChange(),this.$emit("clear")}))},clickHandler(){}}},[["render",function(t,n,o,i,a,l){const r=x(u("u-icon"),e),s=b,_=S;return p(),d(s,{class:g(["u-input",l.inputClass]),style:m([l.wrapperStyle])},{default:c((()=>[f(s,{class:"u-input__content"},{default:c((()=>[t.prefixIcon||t.$slots.prefix?(p(),d(s,{key:0,class:"u-input__content__prefix-icon"},{default:c((()=>[h(t.$slots,"prefix",{},(()=>[f(r,{name:t.prefixIcon,size:"18",customStyle:t.prefixIconStyle},null,8,["name","customStyle"])]),!0)])),_:3})):y("",!0),f(s,{class:"u-input__content__field-wrapper",onClick:l.clickHandler},{default:c((()=>[f(_,{class:"u-input__content__field-wrapper__field",style:m([l.inputStyle]),type:t.type,focus:t.focus,cursor:t.cursor,value:a.innerValue,"auto-blur":t.autoBlur,disabled:t.disabled||t.readonly,maxlength:t.maxlength,placeholder:t.placeholder,"placeholder-style":t.placeholderStyle,"placeholder-class":t.placeholderClass,"confirm-type":t.confirmType,"confirm-hold":t.confirmHold,"hold-keyboard":t.holdKeyboard,"cursor-spacing":t.cursorSpacing,"adjust-position":t.adjustPosition,"selection-end":t.selectionEnd,"selection-start":t.selectionStart,password:t.password||"password"===t.type||!1,ignoreCompositionEvent:t.ignoreCompositionEvent,onInput:l.onInput,onBlur:l.onBlur,onFocus:l.onFocus,onConfirm:l.onConfirm,onKeyboardheightchange:l.onkeyboardheightchange},null,8,["style","type","focus","cursor","value","auto-blur","disabled","maxlength","placeholder","placeholder-style","placeholder-class","confirm-type","confirm-hold","hold-keyboard","cursor-spacing","adjust-position","selection-end","selection-start","password","ignoreCompositionEvent","onInput","onBlur","onFocus","onConfirm","onKeyboardheightchange"])])),_:1},8,["onClick"]),l.isShowClear?(p(),d(s,{key:1,class:"u-input__content__clear",onClick:l.onClear},{default:c((()=>[f(r,{name:"close",size:"11",color:"#ffffff",customStyle:"line-height: 12px"})])),_:1},8,["onClick"])):y("",!0),t.suffixIcon||t.$slots.suffix?(p(),d(s,{key:2,class:"u-input__content__subfix-icon"},{default:c((()=>[h(t.$slots,"suffix",{},(()=>[f(r,{name:t.suffixIcon,size:"18",customStyle:t.suffixIconStyle},null,8,["name","customStyle"])]),!0)])),_:3})):y("",!0)])),_:3})])),_:3},8,["class","style"])}],["__scopeId","data-v-96d3522e"]]);export{C as _};
