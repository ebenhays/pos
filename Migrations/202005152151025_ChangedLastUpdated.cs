namespace PointOfSale.Migrations
{
    using System;
    using System.Data.Entity.Migrations;
    
    public partial class ChangedLastUpdated : DbMigration
    {
        public override void Up()
        {
            AlterColumn("dbo.Categories", "LastUpdatedBy", c => c.String());
            AlterColumn("dbo.Categories", "LastUpdateDate", c => c.DateTime());
            AlterColumn("dbo.Companies", "LastUpdatedBy", c => c.String());
            AlterColumn("dbo.Companies", "LastUpdateDate", c => c.DateTime());
            AlterColumn("dbo.ProductStatus", "LastUpdatedBy", c => c.String());
            AlterColumn("dbo.ProductStatus", "LastUpdateDate", c => c.DateTime());
            AlterColumn("dbo.Suppliers", "LastUpdatedBy", c => c.String());
            AlterColumn("dbo.Suppliers", "LastUpdateDate", c => c.DateTime());
            AlterColumn("dbo.UserRoles", "LastUpdatedBy", c => c.String());
            AlterColumn("dbo.UserRoles", "LastUpdateDate", c => c.DateTime());
            AlterColumn("dbo.Users", "LastUpdatedBy", c => c.String());
            AlterColumn("dbo.Users", "LastUpdateDate", c => c.DateTime());
            AlterColumn("dbo.UserStatus", "LastUpdatedBy", c => c.String());
            AlterColumn("dbo.UserStatus", "LastUpdateDate", c => c.DateTime());
        }
        
        public override void Down()
        {
            AlterColumn("dbo.UserStatus", "LastUpdateDate", c => c.String());
            AlterColumn("dbo.UserStatus", "LastUpdatedBy", c => c.DateTime());
            AlterColumn("dbo.Users", "LastUpdateDate", c => c.String());
            AlterColumn("dbo.Users", "LastUpdatedBy", c => c.DateTime());
            AlterColumn("dbo.UserRoles", "LastUpdateDate", c => c.String());
            AlterColumn("dbo.UserRoles", "LastUpdatedBy", c => c.DateTime());
            AlterColumn("dbo.Suppliers", "LastUpdateDate", c => c.String());
            AlterColumn("dbo.Suppliers", "LastUpdatedBy", c => c.DateTime());
            AlterColumn("dbo.ProductStatus", "LastUpdateDate", c => c.String());
            AlterColumn("dbo.ProductStatus", "LastUpdatedBy", c => c.DateTime());
            AlterColumn("dbo.Companies", "LastUpdateDate", c => c.String());
            AlterColumn("dbo.Companies", "LastUpdatedBy", c => c.DateTime());
            AlterColumn("dbo.Categories", "LastUpdateDate", c => c.String());
            AlterColumn("dbo.Categories", "LastUpdatedBy", c => c.DateTime());
        }
    }
}
