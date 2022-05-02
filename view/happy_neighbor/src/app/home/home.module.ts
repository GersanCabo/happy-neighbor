import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListCommunitiesComponent } from './components/list-communities/list-communities.component';
import { CommunityCardComponent } from './components/community-card/community-card.component';



@NgModule({
  declarations: [
    ListCommunitiesComponent,
    CommunityCardComponent
  ],
  imports: [
    CommonModule
  ]
})
export class HomeModule { }
