import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'shortenText'
})
export class ShortenTextPipe implements PipeTransform {

  transform(value: any, ...args: unknown[]): unknown {
    let result = value;
    if (typeof value != "string") {
      result = "";
    } else if (value.length > 40) {
      result = value.slice(0,40) + "...";
    }
    return result;
  }

}
