import{r as e,y as a,z as i,A as s,G as n,C as t,H as l,N as d}from"./index-BpNRNyt1.js";import{a as c}from"./index.oyXK7971.js";import{_ as r}from"./_plugin-vue_export-helper.BCo6x5W8.js";const o=r({data:()=>({daily:""}),mounted(){c().then((e=>{this.daily=e.data}))},methods:{handleJumpCheck(){e({url:"/pages/index-check-in-center/index-check-in-center"})},handleJumpTask(){e({url:"/pages/newbie-task/newbie-task"})},handleJumpRecommentedFriends(){e({url:"/pages/recommended-friends/recommended-friends"})}}},[["render",function(e,c,r,o,m,p){const u=d,k=t;return m.daily?(a(),i(k,{key:0,class:"activity-center-page"},{default:s((()=>["1"===m.daily.daily_welfare.activity.novice_gift_pack?(a(),i(k,{key:0,class:"list-box"},{default:s((()=>[l(u,{src:"/assets/active4-Br475Dgz.png",onClick:p.handleJumpTask},null,8,["onClick"])])),_:1})):n("",!0),"1"===m.daily.daily_welfare.activity.daily_attendance?(a(),i(k,{key:1,class:"list-box"},{default:s((()=>[l(u,{src:"/assets/active1-B4twWVX2.png",onClick:p.handleJumpCheck},null,8,["onClick"])])),_:1})):n("",!0),"1"===m.daily.daily_welfare.activity.friend_invitation?(a(),i(k,{key:2,class:"list-box"},{default:s((()=>[l(u,{src:"/assets/active5-ZeF3PFR0.png",onClick:p.handleJumpRecommentedFriends},null,8,["onClick"])])),_:1})):n("",!0)])),_:1})):n("",!0)}],["__scopeId","data-v-76f1476a"]]);export{o as default};
