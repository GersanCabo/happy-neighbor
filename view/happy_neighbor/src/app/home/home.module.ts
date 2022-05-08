import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListCommunitiesComponent } from './components/list-communities/list-communities.component';
import { CommunityCardComponent } from './components/community-card/community-card.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';



@NgModule({
  declarations: [
    ListCommunitiesComponent,
    CommunityCardComponent
  ],
  imports: [
    CommonModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule
  ],
  exports: [
    ListCommunitiesComponent
  ]
})
export class HomeModule { }
