import{W as t,X as a,x as s,y as e,z as o,A as l,C as i,H as r,I as n,D as u,E as d,F as m,J as p}from"./index-CxzTsawa.js";import{_ as f}from"./u-empty.BYlPWjFR.js";import{r as h}from"./uni-app.es.BDn5iwHj.js";import{_ as c}from"./u-loadmore.gSlu5agk.js";import{P as g}from"./index.C-ywEe0a.js";import{_}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.R77ervPf.js";import"./u-line.C1bQtxUb.js";import"./u-loading-icon.DIVtFoQ4.js";const j=_({data:()=>({lists:[],lapage:1,page:1,status:"loadmore"}),mounted(){this.getData()},methods:{onReachBottom(){"nomore"!=this.status&&(this.status="loading",this.getData())},getData(){t({title:"请求中..."}),g({lang:"zh-cn",page:this.page}).then((t=>{if(console.log(t),0===t.error){if(!t.data)return;if(0==t.data.length)return this.status="nomore",void a();t.data.length>9?this.status="loadmore":this.status="nomore";for(let a=0;a<t.data.length;a++)this.lists.push(t.data[a]);this.page++}})).finally((()=>{a()}))}}},[["render",function(t,a,g,_,j,y){const x=i,k=h(s("up-empty"),f),D=h(s("up-loadmore"),c);return e(),o(x,{class:"promotion-detail-page"},{default:l((()=>[r(x,{class:"list-header"},{default:l((()=>[r(x,null,{default:l((()=>[n("邀请用户")])),_:1}),r(x,null,{default:l((()=>[n("注册时间")])),_:1})])),_:1}),j.lists.length?(e(),o(x,{key:0},{default:l((()=>[(e(!0),u(m,null,d(j.lists,((t,a)=>(e(),o(x,{class:"list",key:a},{default:l((()=>[r(x,null,{default:l((()=>[n(p(t.mobile),1)])),_:2},1024),r(x,null,{default:l((()=>[r(x,null,{default:l((()=>[n(p(t.create_time_m),1)])),_:2},1024),r(x,{style:{color:"#999"}},{default:l((()=>[n(p(t.create_time),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1})):(e(),o(k,{key:1,mode:"data",width:"160","margin-top":"450rpx",icon:"/static/Vertretung/noMoreData@2x.png"})),r(D,{status:j.status,onClick:y.onReachBottom},null,8,["status","onClick"])])),_:1})}],["__scopeId","data-v-bd322292"]]);export{j as default};
