import{W as t,X as a,x as s,y as e,z as l,A as o,C as i,H as n,I as u,D as r,E as d,F as m,J as p}from"./index-Br34eEhS.js";import{_ as f}from"./u-empty.B7Sg4x7o.js";import{r as h}from"./uni-app.es.-KBXnIhR.js";import{_}from"./u-loadmore.RbKvhosc.js";import{P as c}from"./index.DFLHdVnw.js";import{_ as g}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.B55BycVW.js";import"./u-line.B9GtlKhw.js";import"./u-loading-icon.DBZG26k-.js";const j=g({data:()=>({lists:[],lapage:1,page:1,status:"loadmore"}),mounted(){this.getData()},methods:{onReachBottom(){"nomore"!=this.status&&(this.status="loading",this.getData())},getData(){t({title:"请求中..."}),c({lang:"zh-cn",page:this.page}).then((t=>{if(0==t.data.length)return this.status="nomore",void a();t.data.length>9?this.status="loadmore":this.status="nomore";for(let a=0;a<t.data.length;a++)this.lists.push(t.data[a]);this.page++})).finally((()=>{a()}))}}},[["render",function(t,a,c,g,j,y){const x=i,k=h(s("up-empty"),f),D=h(s("up-loadmore"),_);return e(),l(x,{class:"promotion-detail-page"},{default:o((()=>[n(x,{class:"list-header"},{default:o((()=>[n(x,null,{default:o((()=>[u("邀请用户")])),_:1}),n(x,null,{default:o((()=>[u("注册时间")])),_:1}),n(x,null,{default:o((()=>[u("返佣截至")])),_:1})])),_:1}),j.lists.length?(e(),l(x,{key:0},{default:o((()=>[(e(!0),r(m,null,d(j.lists,((t,a)=>(e(),l(x,{class:"list",key:a},{default:o((()=>[n(x,null,{default:o((()=>[u(p(t.mobile),1)])),_:2},1024),n(x,null,{default:o((()=>[n(x,null,{default:o((()=>[u(p(t.create_time_m),1)])),_:2},1024),n(x,{style:{color:"#999"}},{default:o((()=>[u(p(t.create_time),1)])),_:2},1024)])),_:2},1024),n(x,null,{default:o((()=>[u(p(t.back_end),1)])),_:2},1024)])),_:2},1024)))),128))])),_:1})):(e(),l(k,{key:1,mode:"data",width:"160","margin-top":"450rpx",icon:"/static/Vertretung/noMoreData@2x.png"})),n(D,{status:j.status,onClick:y.onReachBottom},null,8,["status","onClick"])])),_:1})}],["__scopeId","data-v-3028ca46"]]);export{j as default};
