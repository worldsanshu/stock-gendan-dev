import{W as t,X as a,x as s,y as e,z as o,B as l,C as i,H as r,I as n,D as u,E as d,F as m,J as p}from"./index-hU8OKPmH.js";import{_ as f}from"./u-empty.BsWToqDu.js";import{r as h}from"./uni-app.es.DADla7ur.js";import{_ as g}from"./u-loadmore.DqssgiGm.js";import{P as c}from"./index.DUzjlh-0.js";import{_}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.BX8bEo6J.js";import"./u-line.ILQrRGaQ.js";import"./u-loading-icon.DGLmxP69.js";const j=_({data:()=>({lists:[],lapage:1,page:1,status:"loadmore"}),mounted(){this.getData()},methods:{onReachBottom(){"nomore"!=this.status&&(this.status="loading",this.getData())},getData(){t({title:"请求中..."}),c({lang:"zh-cn",page:this.page}).then((t=>{if(console.log(t),0===t.error){if(!t.data)return;if(0==t.data.length)return this.status="nomore",void a();t.data.length>9?this.status="loadmore":this.status="nomore";for(let a=0;a<t.data.length;a++)this.lists.push(t.data[a]);this.page++}})).finally((()=>{a()}))}}},[["render",function(t,a,c,_,j,y){const x=l,D=h(s("up-empty"),f),k=h(s("up-loadmore"),g);return i(),e(x,{class:"promotion-detail-page"},{default:o((()=>[r(x,{class:"list-header"},{default:o((()=>[r(x,null,{default:o((()=>[n("邀请用户")])),_:1}),r(x,null,{default:o((()=>[n("注册时间")])),_:1})])),_:1}),j.lists.length?(i(),e(x,{key:0},{default:o((()=>[(i(!0),u(m,null,d(j.lists,((t,a)=>(i(),e(x,{class:"list",key:a},{default:o((()=>[r(x,null,{default:o((()=>[n(p(t.mobile),1)])),_:2},1024),r(x,null,{default:o((()=>[r(x,null,{default:o((()=>[n(p(t.create_time_m),1)])),_:2},1024),r(x,{style:{color:"#999"}},{default:o((()=>[n(p(t.create_time),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1})):(i(),e(D,{key:1,mode:"data",width:"160","margin-top":"450rpx",icon:"/static/Vertretung/noMoreData@2x.png"})),r(k,{status:j.status,onClick:y.onReachBottom},null,8,["status","onClick"])])),_:1})}],["__scopeId","data-v-bd322292"]]);export{j as default};
