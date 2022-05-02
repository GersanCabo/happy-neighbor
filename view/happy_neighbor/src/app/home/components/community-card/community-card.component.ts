import { Input } from '@angular/core';
import { Component, OnInit } from '@angular/core';
import { Community } from 'src/app/global/class/Community';

@Component({
  selector: 'app-community-card',
  templateUrl: './community-card.component.html',
  styleUrls: ['./community-card.component.scss']
})
export class CommunityCardComponent implements OnInit {

  @Input() community: Community|null = null;

  constructor() { }

  ngOnInit(): void {
  }

}
