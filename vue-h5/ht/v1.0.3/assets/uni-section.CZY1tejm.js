import{y as t,z as e,A as i,H as s,B as l,a9 as n,aa as o,I as a,J as d,G as r,C as u,O as c}from"./index-MDKEdHpS.js";import{_ as p}from"./_plugin-vue_export-helper.BCo6x5W8.js";const _=p({name:"UniSection",emits:["click"],props:{type:{type:String,default:""},title:{type:String,required:!0,default:""},titleFontSize:{type:String,default:"14px"},titleColor:{type:String,default:"#333"},subTitle:{type:String,default:""},subTitleFontSize:{type:String,default:"12px"},subTitleColor:{type:String,default:"#999"},padding:{type:[Boolean,String],default:!1}},computed:{_padding(){return"string"==typeof this.padding?this.padding:this.padding?"10px":""}},watch:{title(t){uni.report&&""!==t&&uni.report("title",t)}},methods:{onClick(){this.$emit("click")}}},[["render",function(p,_,f,y,g,h){const S=u,b=c;return t(),e(S,{class:"uni-section"},{default:i((()=>[s(S,{class:"uni-section-header",onClick:h.onClick},{default:i((()=>[f.type?(t(),e(S,{key:0,class:l(["uni-section-header__decoration",f.type])},null,8,["class"])):n(p.$slots,"decoration",{key:1},void 0,!0),s(S,{class:"uni-section-header__content"},{default:i((()=>[s(b,{style:o({"font-size":f.titleFontSize,color:f.titleColor}),class:l(["uni-section__content-title",{distraction:!f.subTitle}])},{default:i((()=>[a(d(f.title),1)])),_:1},8,["style","class"]),f.subTitle?(t(),e(b,{key:0,style:o({"font-size":f.subTitleFontSize,color:f.subTitleColor}),class:"uni-section-header__content-sub"},{default:i((()=>[a(d(f.subTitle),1)])),_:1},8,["style"])):r("",!0)])),_:1}),s(S,{class:"uni-section-header__slot-right"},{default:i((()=>[n(p.$slots,"right",{},void 0,!0)])),_:3})])),_:3},8,["onClick"]),s(S,{class:"uni-section-content",style:o({padding:h._padding})},{default:i((()=>[n(p.$slots,"default",{},void 0,!0)])),_:3},8,["style"])])),_:3})}],["__scopeId","data-v-0a8818d5"]]);export{_};
