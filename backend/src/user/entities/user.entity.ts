import { Column, CreateDateColumn, Entity, PrimaryGeneratedColumn } from "typeorm";

@Entity()
export class User {
   @PrimaryGeneratedColumn()
   id: number;

   @Column({nullable: false})
   first_name: string;

   @Column({nullable: false})
   last_name: string;

   @Column({unique: true, nullable: false})
   email: string;

   @Column({nullable: false})
   password: string;

   @Column({nullable: false})
   role: string;

   @CreateDateColumn()
   createdAt: Date;
}
