namespace PointOfSale.Migrations
{
    using System;
    using System.Data.Entity.Migrations;
    
    public partial class InitialMigrations1 : DbMigration
    {
        public override void Up()
        {
            DropIndex("dbo.Categories", new[] { "Code" });
            DropIndex("dbo.Users", new[] { "UserName" });
            AlterColumn("dbo.Categories", "CreationDate", c => c.DateTime());
            AlterColumn("dbo.Categories", "CreatedBy", c => c.String());
            AlterColumn("dbo.Companies", "CreationDate", c => c.DateTime());
            AlterColumn("dbo.Companies", "CreatedBy", c => c.String());
            AlterColumn("dbo.ProductStatus", "CreationDate", c => c.DateTime());
            AlterColumn("dbo.ProductStatus", "CreatedBy", c => c.String(maxLength: 50));
            AlterColumn("dbo.Retails", "ExpiryDate", c => c.DateTime());
            AlterColumn("dbo.Retails", "CreatedDate", c => c.DateTime());
            AlterColumn("dbo.Retails", "LastUpdateDate", c => c.DateTime());
            AlterColumn("dbo.Suppliers", "CreationDate", c => c.DateTime());
            AlterColumn("dbo.Suppliers", "CreatedBy", c => c.String(maxLength: 50));
            AlterColumn("dbo.UserRoles", "CreationDate", c => c.DateTime());
            AlterColumn("dbo.UserRoles", "CreatedBy", c => c.String(maxLength: 50));
            AlterColumn("dbo.Users", "CreationDate", c => c.DateTime());
            AlterColumn("dbo.Users", "CreatedBy", c => c.String(maxLength: 50));
            AlterColumn("dbo.UserStatus", "CreationDate", c => c.DateTime());
            AlterColumn("dbo.UserStatus", "CreatedBy", c => c.String(maxLength: 50));
            AlterColumn("dbo.WholeSales", "ExpiryDate", c => c.DateTime());
            AlterColumn("dbo.WholeSales", "CreatedDate", c => c.DateTime());
            AlterColumn("dbo.WholeSales", "LastUpdateDate", c => c.DateTime());
            DropColumn("dbo.Categories", "Code");
            DropColumn("dbo.Suppliers", "Code");
        }
        
        public override void Down()
        {
            AddColumn("dbo.Suppliers", "Code", c => c.String(nullable: false));
            AddColumn("dbo.Categories", "Code", c => c.String(nullable: false, maxLength: 100));
            AlterColumn("dbo.WholeSales", "LastUpdateDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.WholeSales", "CreatedDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.WholeSales", "ExpiryDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.UserStatus", "CreatedBy", c => c.String(nullable: false, maxLength: 50));
            AlterColumn("dbo.UserStatus", "CreationDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.Users", "CreatedBy", c => c.String(nullable: false, maxLength: 50));
            AlterColumn("dbo.Users", "CreationDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.UserRoles", "CreatedBy", c => c.String(nullable: false, maxLength: 50));
            AlterColumn("dbo.UserRoles", "CreationDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.Suppliers", "CreatedBy", c => c.String(nullable: false, maxLength: 50));
            AlterColumn("dbo.Suppliers", "CreationDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.Retails", "LastUpdateDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.Retails", "CreatedDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.Retails", "ExpiryDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.ProductStatus", "CreatedBy", c => c.String(nullable: false, maxLength: 50));
            AlterColumn("dbo.ProductStatus", "CreationDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.Companies", "CreatedBy", c => c.String(nullable: false, maxLength: 50));
            AlterColumn("dbo.Companies", "CreationDate", c => c.DateTime(nullable: false));
            AlterColumn("dbo.Categories", "CreatedBy", c => c.String(nullable: false, maxLength: 50));
            AlterColumn("dbo.Categories", "CreationDate", c => c.DateTime(nullable: false));
            CreateIndex("dbo.Users", "UserName", unique: true);
            CreateIndex("dbo.Categories", "Code", unique: true);
        }
    }
}
