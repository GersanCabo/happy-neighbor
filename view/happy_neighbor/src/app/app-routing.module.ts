import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './auth/components/login/login.component';
import { CommunityPageComponent } from './community-portal/components/community-page/community-page.component';
import { ListCommunitiesComponent } from './home/components/list-communities/list-communities.component';

const routes: Routes = [
  {
    path: '',
    component: ListCommunitiesComponent
  },
  {
    path: 'login',
    component: LoginComponent
  },
  {
    path: 'community/:id',
    component: CommunityPageComponent
  },
  {
    path: '**',
    component: ListCommunitiesComponent
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
