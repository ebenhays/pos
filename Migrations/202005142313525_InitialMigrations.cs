namespace PointOfSale.Migrations
{
    using System;
    using System.Data.Entity.Migrations;
    
    public partial class InitialMigrations : DbMigration
    {
        public override void Up()
        {
            CreateTable(
                "dbo.Categories",
                c => new
                    {
                        Id = c.Int(nullable: false, identity: true),
                        Name = c.String(nullable: false, maxLength: 100),
                        Code = c.String(nullable: false, maxLength: 100),
                        CreationDate = c.DateTime(nullable: false),
                        CreatedBy = c.String(nullable: false, maxLength: 50),
                        LastUpdatedBy = c.DateTime(),
                        LastUpdateDate = c.String(),
                    })
                .PrimaryKey(t => t.Id)
                .Index(t => t.Code, unique: true);
            
            CreateTable(
                "dbo.Companies",
                c => new
                    {
                        Id = c.Int(nullable: false, identity: true),
                        Name = c.String(nullable: false, maxLength: 100),
                        Address = c.String(maxLength: 100),
                        ContactNo = c.String(nullable: false),
                        EmailAddress = c.String(maxLength: 50),
                        CreationDate = c.DateTime(nullable: false),
                        CreatedBy = c.String(nullable: false, maxLength: 50),
                        LastUpdatedBy = c.DateTime(),
                        LastUpdateDate = c.String(),
                    })
                .PrimaryKey(t => t.Id);
            
            CreateTable(
                "dbo.ProductStatus",
                c => new
                    {
                        Id = c.Int(nullable: false, identity: true),
                        Status = c.String(nullable: false, maxLength: 50),
                        CreationDate = c.DateTime(nullable: false),
                        CreatedBy = c.String(nullable: false, maxLength: 50),
                        LastUpdatedBy = c.DateTime(),
                        LastUpdateDate = c.String(),
                    })
                .PrimaryKey(t => t.Id);
            
            CreateTable(
                "dbo.Retails",
                c => new
                    {
                        Id = c.Int(nullable: false, identity: true),
                        StockNo = c.String(nullable: false, maxLength: 100),
                        BarcodeNo = c.String(maxLength: 50),
                        UnitType = c.String(),
                        CostPrice = c.Single(nullable: false),
                        PricePerItem = c.Single(nullable: false),
                        Discount = c.Single(nullable: false),
                        //TotalCost = c.Single(nullable: false),
                        Vat = c.Single(nullable: false),
                        TotalQty = c.Int(nullable: false),
                        ManufDate = c.DateTime(nullable: false),
                        ExpiryDate = c.DateTime(nullable: false),
                        PromptDays = c.Int(nullable: false),
                        MinLevelStock = c.Int(nullable: false),
                        FDBNo = c.String(),
                        CreatedDate = c.DateTime(nullable: false),
                        CreatedBy = c.String(),
                        LastUpdatedBy = c.String(),
                        LastUpdateDate = c.DateTime(nullable: false),
                        Category_Id = c.Int(nullable: false),
                        Supplier_Id = c.Int(nullable: false),
                    })
                .PrimaryKey(t => t.Id)
                .ForeignKey("dbo.Categories", t => t.Category_Id, cascadeDelete: true)
                .ForeignKey("dbo.Suppliers", t => t.Supplier_Id, cascadeDelete: true)
                .Index(t => t.StockNo, unique: true)
                .Index(t => t.Category_Id)
                .Index(t => t.Supplier_Id);
            Sql("ALTER TABLE dbo.Retails ADD TotalCost AS ((PricePerItem * TotalQty)+ ((Vat / 100)*(PricePerItem * TotalQty))-((Discount / 100)*(PricePerItem * TotalQty))) ");

            CreateTable(
                "dbo.Suppliers",
                c => new
                    {
                        Id = c.Int(nullable: false, identity: true),
                        Name = c.String(nullable: false, maxLength: 100),
                        Code = c.String(nullable: false),
                        Address = c.String(),
                        ContactNo = c.String(nullable: false),
                        CreationDate = c.DateTime(nullable: false),
                        CreatedBy = c.String(nullable: false, maxLength: 50),
                        LastUpdatedBy = c.DateTime(),
                        LastUpdateDate = c.String(),
                    })
                .PrimaryKey(t => t.Id);
            
            CreateTable(
                "dbo.UserRoles",
                c => new
                    {
                        Id = c.Int(nullable: false, identity: true),
                        Name = c.String(nullable: false, maxLength: 50),
                        CreationDate = c.DateTime(nullable: false),
                        CreatedBy = c.String(nullable: false, maxLength: 50),
                        LastUpdatedBy = c.DateTime(),
                        LastUpdateDate = c.String(),
                    })
                .PrimaryKey(t => t.Id);
            
            CreateTable(
                "dbo.Users",
                c => new
                    {
                        Id = c.Int(nullable: false, identity: true),
                        UserName = c.String(nullable: false, maxLength: 50),
                        FullName = c.String(nullable: false, maxLength: 100),
                        CreationDate = c.DateTime(nullable: false),
                        CreatedBy = c.String(nullable: false, maxLength: 50),
                        LastUpdatedBy = c.DateTime(),
                        LastUpdateDate = c.String(),
                        UserRole_Id = c.Int(nullable: false),
                        UserStatus_Id = c.Int(nullable: false),
                    })
                .PrimaryKey(t => t.Id)
                .ForeignKey("dbo.UserRoles", t => t.UserRole_Id, cascadeDelete: true)
                .ForeignKey("dbo.UserStatus", t => t.UserStatus_Id, cascadeDelete: true)
                .Index(t => t.UserName, unique: true)
                .Index(t => t.UserRole_Id)
                .Index(t => t.UserStatus_Id);
            
            CreateTable(
                "dbo.UserStatus",
                c => new
                    {
                        Id = c.Int(nullable: false, identity: true),
                        Status = c.String(nullable: false, maxLength: 50),
                        CreationDate = c.DateTime(nullable: false),
                        CreatedBy = c.String(nullable: false, maxLength: 50),
                        LastUpdatedBy = c.DateTime(),
                        LastUpdateDate = c.String(),
                    })
                .PrimaryKey(t => t.Id);
            
            CreateTable(
                "dbo.WholeSales",
                c => new
                    {
                        Id = c.Int(nullable: false, identity: true),
                        StockNo = c.String(nullable: false, maxLength: 100),
                        BarcodeNo = c.String(maxLength: 50),
                        UnitType = c.String(),
                        CostPrice = c.Single(nullable: false),
                        PricePerItem = c.Single(nullable: false),
                        Discount = c.Single(nullable: false),
                        //TotalCost = c.Single(nullable: false),
                        Vat = c.Single(nullable: false),
                        TotalQty = c.Int(nullable: false),
                        ManufDate = c.DateTime(nullable: false),
                        ExpiryDate = c.DateTime(nullable: false),
                        PromptDays = c.Int(nullable: false),
                        MinLevelStock = c.Int(nullable: false),
                        FDBNo = c.String(),
                        CreatedDate = c.DateTime(nullable: false),
                        CreatedBy = c.String(),
                        LastUpdatedBy = c.String(),
                        LastUpdateDate = c.DateTime(nullable: false),
                        Category_Id = c.Int(nullable: false),
                        Supplier_Id = c.Int(nullable: false),
                    })
                .PrimaryKey(t => t.Id)
                .ForeignKey("dbo.Categories", t => t.Category_Id, cascadeDelete: true)
                .ForeignKey("dbo.Suppliers", t => t.Supplier_Id, cascadeDelete: true)
                .Index(t => t.StockNo, unique: true)
                .Index(t => t.Category_Id)
                .Index(t => t.Supplier_Id);
            Sql("ALTER TABLE dbo.WholeSales ADD TotalCost AS ((PricePerItem * TotalQty)+ ((Vat / 100)*(PricePerItem * TotalQty))-((Discount / 100)*(PricePerItem * TotalQty))) ");
        }
        
        public override void Down()
        {
            DropForeignKey("dbo.WholeSales", "Supplier_Id", "dbo.Suppliers");
            DropForeignKey("dbo.WholeSales", "Category_Id", "dbo.Categories");
            DropForeignKey("dbo.Users", "UserStatus_Id", "dbo.UserStatus");
            DropForeignKey("dbo.Users", "UserRole_Id", "dbo.UserRoles");
            DropForeignKey("dbo.Retails", "Supplier_Id", "dbo.Suppliers");
            DropForeignKey("dbo.Retails", "Category_Id", "dbo.Categories");
            DropIndex("dbo.WholeSales", new[] { "Supplier_Id" });
            DropIndex("dbo.WholeSales", new[] { "Category_Id" });
            DropIndex("dbo.WholeSales", new[] { "StockNo" });
            DropIndex("dbo.Users", new[] { "UserStatus_Id" });
            DropIndex("dbo.Users", new[] { "UserRole_Id" });
            DropIndex("dbo.Users", new[] { "UserName" });
            DropIndex("dbo.Retails", new[] { "Supplier_Id" });
            DropIndex("dbo.Retails", new[] { "Category_Id" });
            DropIndex("dbo.Retails", new[] { "StockNo" });
            DropIndex("dbo.Categories", new[] { "Code" });
            DropTable("dbo.WholeSales");
            DropTable("dbo.UserStatus");
            DropTable("dbo.Users");
            DropTable("dbo.UserRoles");
            DropTable("dbo.Suppliers");
            DropTable("dbo.Retails");
            DropTable("dbo.ProductStatus");
            DropTable("dbo.Companies");
            DropTable("dbo.Categories");
        }
    }
}
