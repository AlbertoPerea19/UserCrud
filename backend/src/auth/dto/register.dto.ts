import { Transform } from "class-transformer";
import { IsEmail, IsString, MinLength } from "class-validator";

export class RegisterDto {
   @IsString()
   @MinLength(1)
   first_name: string;

   @IsString()
   @MinLength(1)
   last_name: string;

   @IsEmail()
   email: string;

   @IsString()
   role: string;

   @IsString()
   @MinLength(6)
   @Transform(({ value }) => value.trim())
   password: string;
}
