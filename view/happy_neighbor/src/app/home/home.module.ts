import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListCommunitiesComponent } from './components/list-communities/list-communities.component';
import { CommunityCardComponent } from './components/community-card/community-card.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { ShortenTextPipe } from './pipes/shorten-text.pipe';
import { SharedModule } from '../shared/shared.module';



@NgModule({
  declarations: [
    ListCommunitiesComponent,
    CommunityCardComponent,
    ShortenTextPipe
  ],
  imports: [
    CommonModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    SharedModule
  ],
  exports: [
    ListCommunitiesComponent
  ]
})
export class HomeModule { }
